<?php
// 실시간 청크 디코드
// https://tangobean.com/code/fsockopen-read-chunked-page/


/**
 * Download file with fsockopen
 *
 * @see http://tangobean.com
 * @author James Tang<fwsous@gmail.com>
 * @copyright (C) 2013 James Tang.
 */

set_time_limit(600);
ignore_user_abort(true);

//$url = 'http://test.example.com/fetch_file.php?file=testfile.iso';
//$saveToFile = 'tmp.iso';
$url = 'http://test.example.com/fetch_file.php?file=tmp.gz';
$saveToFile = 'tmp.gz';

if (preg_match_all('#http://([^/]+)(/.+)#i', $url, $matches)) {
    $host = $matches[1][0];
    $path = $matches[2][0];
} else {
    die('Invalid URl');
}

$fp = fsockopen($host, 80, $errno, $error, 30);
$readBlockSize = 512;

if ($fp) {

    $wfp = fopen($saveToFile, 'w');

    if ($wfp) {
        $request = "GET $path HTTP/1.1\r\n";
        $request .= "Host: $host\r\n";
        $request .= "Connection: close\r\n";
        $request .= "User-Agent: php-download/1.0\r\n";
        $request .= "\r\n";

        fwrite($fp, $request);

        $body_start = false;
        $md5sum = '';
        $content_length = false;
        $chunk_length = false;

        $startLine = fgets($fp, 128);

        if ($startLine && preg_match('#^HTTP/1.\d?\s+200\s+#', $startLine)) {
            while (!feof($fp)) {
                if (!$body_start) {
                    $header = fgets($fp, 128);
                    echo $header;
                    $colon_pos = strpos($header, ':');
                    $header_name = strtolower(trim(substr($header, 0, $colon_pos)));
                    $header_value = trim(substr($header, $colon_pos+1));
                    if ($header_name == 'content-md5') {
                        $md5sum = bin2hex(base64_decode($header_value));
                    } else if ($header_name == 'content-length') {
                        $content_length = (int) $header_value;
                    }
                    if ($header == "\r\n") {
                        $body_start = true;
                        echo "Reading data...\n";
                    }
                }
                else {

                    if ($content_length !== false && $content_length > 0) {
                        $data = fread($fp, $readBlockSize);
                        fwrite($wfp, $data);
                    } else
                    {
                        if ($chunk_length === false) {
                            $data = trim(fgets($fp, 128));
                            $chunk_length = hexdec($data);
                        }
                        else if ($chunk_length > 0) {
                            $read_length = $chunk_length > $readBlockSize ? $readBlockSize : $chunk_length;
                            $chunk_length -= $read_length;
                            $data = fread($fp, $read_length);
                            fwrite($wfp, $data);
                            if ($chunk_length <= 0) {
                                fseek($fp, 2, SEEK_CUR);
                                $chunk_length = false;
                            }
                        } else {
                            break;
                        }
                    }
                }
            }
        } else {
            echo "Failed to read data: " . $startLine . "\n";
        }

        fclose($wfp);
        if ($md5sum && strlen($md5sum) > 0) {
            $md5sum_check = bin2hex(md5_file($saveToFile, true));
            if ($md5sum_check != $md5sum) {
                echo 'MD5 checksum does not match: ' . $md5sum_check . "\n";
            } else {
                echo "MD5 checksum match\n";
            }
        } else {
            echo "No MD5 checksum detected\n";
        }
        //unlink($saveToFile);
    }

    fclose($fp);
} else {
    echo 'Error: ' . $errno . '#' . $error . "


\n";
}