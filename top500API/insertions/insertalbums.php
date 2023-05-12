<?php
    include("dbconn.php");

    $filename = "album_list.csv";

    // get the resource object (open the file)
    $contents = fopen($filename, "r");
    $row = fgetcsv($contents);

    // loop to read each line from CSV file into $row array
    while ( ($row = fgetcsv($contents)) !== FALSE ) {
            // dump out content of the line
            print_r($row);

            // *************************** ARTIST ***********************************
            
            // Set the database characterset
            $conn->set_charset("utf8mb4");

            // Trim whitespace
            $row[3] = trim($row[3]);

            // Escape string (some artists have ' in name)
            $row[3] = $conn->real_escape_string($row[3]);

            // Decode Character set
            $row[3] = utf8_decode($row[3]);

            // Replace unnecessary characters with a space
            $row[3] = str_replace ( "/[^A-Za-z0-9\/ &-]/", "", $row[3]);

            // Select all artists with current row artist name and query it
            $query = "SELECT * FROM `artist` WHERE `artist_name` = '{$row[3]}'";
            $result = $conn -> query($query);

            $artist = trim($row[3]);
            
            if($result->num_rows == 0) {
                
                // $artist = utf8_encode($artist);

                $query="INSERT INTO `artist` (`artist_id`, `artist_name`) VALUES (NULL, '$artist')";
                $result = $conn -> query($query);
                // $artistID = $conn -> insert_id;

                if(!$result) {
                    echo $conn -> error;
                } else {
                    echo "<p>{$row[3]} has been added to your database.</p>";
                    $artistID = $conn -> insert_id;
                }
                echo "<h1> DATA INSERTED </h1>";
            } else {
                echo "<h1> DATA ALREADY IN TABLE </h1>";
                if(!$result){
                        echo $conn -> error;
                }
                            
                $rowIdSearch = $result->fetch_assoc();

                $artistID=$rowIdSearch['artist_id'];
                $artistName=$rowIdSearch['artist_name'];
            }
        

            // *************************** YEAR ***********************************

            // check to see if database already contains that year
            $query = "SELECT * FROM `year` WHERE year={$row[1]};";

            $result = $conn -> query($query);

            if($result->num_rows == 0) {
                $year = trim($row[1]);
                $year = utf8_encode($year);
                $query = "INSERT INTO `year` (`year_id`, `year`) VALUES (NULL, '{$year}')";
                $result = $conn -> query($query);
                // $yearID = $conn -> insert_id;
                if(!$result) {
                    echo $conn -> error;
                } else {
                    echo "<p>{$row[1]} has been added to your database.</p>";
                    $yearID = $conn -> insert_id;
                }
                echo "<h1> DATA INSERTED </h1>";
                // $yearID = $conn -> insert_id;
            } else {
                // data already in table 
                echo "<h1> DATA ALREADY IN TABLE </h1>";
                $rowIdSearch = $result->fetch_assoc();

                $yearID = $rowIdSearch['year_id'];
            }

            // *************************** SUBGENRE ***********************************

            $subgenre = trim($row[5]);
            $subgenre = utf8_encode($subgenre);

            $subgenre = explode(",", $subgenre);

            $arrayLength = count($subgenre);

            // Store various sub genre IDs
            $subgenreArrayID = array();

            for($i = 0; $i < $arrayLength; $i++) {
                $subAddToDB = trim($subgenre[$i]);
                $query = "SELECT * FROM `sub_genre` WHERE subgenre_name='{$subAddToDB}';";
                $result = $conn -> query($query);

                if($result->num_rows == 0) {
                    $query = "INSERT INTO `sub_genre` (`subgenre_id`, `subgenre_name`) VALUES (NULL, '{$subAddToDB}')";
                    $result = $conn -> query($query);
                    // $subgenreID = $conn -> insert_id;
                    if(!$result) {
                        echo $conn -> error;
                    } else {
                        echo "<p>{$subAddToDB} has been added to your database.</p>";
                        $subgenreID = $conn -> insert_id;
                        $subgenreArrayID[] = (int)$subgenreID;
                    }
                        echo "<h1> DATA INSERTED </h1>";
                    // $subgenreID = $conn -> insert_id;
                } else {
                    // data already in table 
                    echo "<h1> DATA ALREADY IN TABLE </h1>";
                    // $lastSubgenreID = $conn -> insert_id;
                    $rowIdSearch = $result->fetch_assoc();

                    $subgenreID = $rowIdSearch['subgenre_id'];
                    $subgenreArrayID[] = (int)$subgenreID;
                }
            }

            // *************************** ALBUM ***********************************

            $albumrankingint = intval($row[0]);
            $album = $conn->real_escape_string($row[2]);

            $query = "SELECT * FROM `album` WHERE album_name = '$album';";
            $result = $conn -> query($query);

            $album = trim($album);
            
            $album = utf8_encode($album);
            $album = str_replace('','a',$album);
        
            
            $query = "INSERT INTO `album` (`album_id`, `album_name`, `album_ranking`, `year_id`, `artist_id`) VALUES (NULL, '$album', $albumrankingint, $yearID, $artistID)";
            $result = $conn -> query($query);
            $albumID = $conn -> insert_id;

            if(!$result) {
                echo $conn -> error;
            } else {
                echo "<p>{$row[0]} and {$row[2]} has been added to your database.</p>";
            }

            // *************************** GENRE ***********************************

            $genre = trim($row[4]);
            $genre = utf8_encode($genre);
            // $genre = str_replace ( "[^a-zA-Z0-9]", "", $genre);
            // $genre = preg_replace('/[^A-Za-z0-9\-]/', '', $genre);

            $genreArray = explode(",", $genre);

            print_r($genreArray);
            $arrayGenreLength = count($genreArray);
            print_r($arrayGenreLength);

            $genreArrayID = array();

            for($i = 0; $i < $arrayGenreLength; $i++) {
                $genreAddToDB = trim($genreArray[$i]);
                if(strstr($genreAddToDB, 'Country')) {
                    $genreAddToDB = preg_replace('/[^A-Za-z0-9\/ -]/', '', $genreAddToDB);
                    $genreAddToDB = trim($genreAddToDB);
                } else {
                    $genreAddToDB = preg_replace('/[^A-Za-z0-9\/ &-]/', '', $genreAddToDB);
                }
                
                $query = "SELECT * FROM `genre` WHERE genre_name = '{$genreAddToDB}';";
                $result = $conn -> query($query);
                // $rowIdSearch = $result->fetch_assoc();
                // $genreID = $rowIdSearch['album_id'];
                print_r($result);
                    if($result->num_rows == 0) {
                        $query = "INSERT INTO `genre` (`genre_id`, `genre_name`) VALUES (NULL, '{$genreAddToDB}')";
                        $result = $conn -> query($query);
                        // $genreID = $conn -> insert_id;
                        if(!$result) {
                            echo $conn -> error;
                        } else {
                            echo "<p>{$genreAddToDB} has been added to your database.</p>";
                            $genreID = $conn -> insert_id;
                            $genreArrayID[] = $genreID;
                        }
                            echo "<h1> DATA INSERTED </h1>";
                        // $lastGenreID = $conn -> insert_id;
                    } else {
                        // data already in table 
                        echo "<h1> DATA ALREADY IN TABLE </h1>";
                        // $lastGenreID = $conn -> insert_id;
                        $rowIdSearch = $result->fetch_assoc();
                        $genreID = $rowIdSearch['genre_id'];
                        $genreArrayID[] = $genreID;
                    }
            }


            // *********** ALBUM_SUB_GENRE ****************
            print_r("ALBUM SUB GENRE ARRAYYYY ::==============");
            print_r($subgenreArrayID);
            $subGenreLoopLimit = count($subgenreArrayID);
            for($i=0; $i< $subGenreLoopLimit; $i++) {

                $query = "INSERT INTO `album_subgenre` (`album_subgenre_id`, `album_id`, `sub_genre_id`) VALUES (NULL, $albumID, $subgenreArrayID[$i])";

                $result = $conn -> query($query);
                if(!$result) {
                    echo $conn -> error;
                } else {
                    echo "<p>{$albumID}, {$subgenreArrayID[$i]} has been added to ALBUM_SUB_GENRE.</p>";
                }
            }

            // *********** ALBUM_GENRE ****************
            print_r("ALBUM GENRE ARRAYYYY ::==============");
            print_r($genreArrayID);
            $genreArrayIDLoopLimit = count($genreArrayID);
            for($i=0; $i< $genreArrayIDLoopLimit; $i++) {

                $query = "INSERT INTO `album_genre` (`album_genre_id`, `album_id`, `genre_id`) VALUES (NULL, $albumID, $genreArrayID[$i])";

                $result = $conn -> query($query);
                if(!$result) {
                    echo $conn -> error;
                } else {
                    echo "<p>{$genreArrayID[$i]} has been added to ALBUM_SUB_GENRE.</p>";
                }
            }

            // *********** ARTIST_GENRE ****************
            print_r("ARTIST GENRE ARRAYYYY ::==============");
            print_r($genreArrayID);
            $genreArrayIDLoopLimit = count($genreArrayID);

            for($i=0; $i< $genreArrayIDLoopLimit; $i++) {

                $query = "SELECT * FROM `artist_genre` WHERE artist_id=$artistID && genre_id=$genreArrayID[$i]";
                $result = $conn -> query($query);
                print_r("ARTIST ID AND GENREE ::::::: ");
                print_r($result);

                if($result->num_rows == 0) {
                    $query = "INSERT INTO `artist_genre` (`artist_genre_id`, `artist_id`, `genre_id`) VALUES (NULL, $artistID, $genreArrayID[$i])";

                    $result = $conn -> query($query);
                    if(!$result) {
                        echo $conn -> error;
                    } else {
                        echo "<p>{$genreArrayID[$i]} has been added to $artistID.</p>";
                    }
                }
                
            }

    }


?>