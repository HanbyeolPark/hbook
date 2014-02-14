<?php
    /*
		ISBN Tag 변환 코드

        a) ISBN:[13digit]/[10digit]
        b) ISBN:[13digit]
    */
    class ISBNTag {

        var $isbn13 = "";
        var $isbn10 = "";

        function ISBNTag($tag) {
            if (preg_match("/^ISBN:[0-9]{12}[0-9Xx]/", $tag)) {
                $this->isbn13 = substr($tag, 5, 13);

                if (preg_match("/\/[0-9]{9}[0-9Xx]$/", $tag)) {
                    $this->isbn10 = substr($tag, -10);
                } else {
                    $this->isbn10 = substr($this->isbn13, 3);
                }
            }
        }

        function getISBN13() {
            return $this->isbn13;
        }

        function getISBN10() {
            return $this->isbn10;
        }

        function isValid() {
            return (!empty($this->isbn13) && !empty($this->isbn10));
        }
    }
?>