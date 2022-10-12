<?php // BUILD: Remove line

/**
 * A class for storing a single (complete) line of the iCal file.
 * Will find the line-type, the arguments and the data of the file and
 * store them.
 *
 * The line-type can be found by querying getIdent(), data via either
 * getData() or typecasting to a string.
 * Params can be access via the ArrayAccess. A iterator is also avilable
 * to iterator over the params.
 *
 * @package SG_iCalReader
 * @author Morten Fangel (C) 2008
 * @license http://creativecommons.org/licenses/by-sa/2.5/dk/deed.en_GB CC-BY-SA-DK
 */
class SG_iCal_Line implements ArrayAccess, Countable, IteratorAggregate {
        protected $ident;
        protected $data;
        protected $params = array();

        protected $replacements = array('from'=>array('\\,', '\\n', '\\;', '\\:', '\\"'), 'to'=>array(',', "\n", ';', ':', '"'));

        /**
         * Constructs a new line.
         */
        public function __construct( $line ) {
                $split = strpos($line, ':');
                $idents = explode(';', substr($line, 0, $split));
                $ident = strtolower(array_shift($idents));

                $data = trim(substr($line, $split+1));
                $data = str_replace($this->replacements['from'], $this->replacements['to'], $data);

                $params = array();
                foreach( $idents AS $v) {
                        list($k, $v) = explode('=', $v);
                        $params[ strtolower($k) ] = $v;
                }

                $this->ident = $ident;
                $this->params = $params;
                $this->data = $data;
        }

        /**
         * Is this line the begining of a new block?
         * @return bool
         */
        public function isBegin() {
                return $this->ident == 'begin';
        }

        /**
         * Is this line the end of a block?
         * @return bool
         */
        public function isEnd() {
                return $this->ident == 'end';
        }

        /**
         * Returns the line-type (ident) of the line
         * @return string
         */
        public function getIdent() {
                return $this->ident;
        }

        /**
         * Returns the content of the line
         * @return string
         */
        public function getData() {
                return $this->data;
        }

        /**
         * Returns the content of the line
         * @return string
         */
        public function getDataAsArray() {
                if (strpos($this->data,",") !== false) {
                        return explode(",",$this->data);
                }
                else
                        return array($this->data);
        }

        /**
         * A static helper to get a array of SG_iCal_Line's, and calls
         * getData() on each of them to lay the data "bare"..
         *
         * @param SG_iCal_Line[]
         * @return array
         */
        public static function Remove_Line($arr) {
                $rtn = array();
                foreach( $arr AS $k => $v ) {
                        if(is_array($v)) {
                                $rtn[$k] = self::Remove_Line($v);
                        } elseif( $v instanceof SG_iCal_Line ) {
                                $rtn[$k] = $v->getData();
                        } else {
                                $rtn[$k] = $v;
                        }
                }
                return $rtn;
        }

        /**
         * @see ArrayAccess.offsetExists
         */
        #[\ReturnTypeWillChange]
        public function offsetExists( $param ):bool {
                return isset($this->params[ strtolower($param) ]);
        }

        /**
         * @see ArrayAccess.offsetGet
         */
        #[\ReturnTypeWillChange]
        public function offsetGet( $param ):mixed {
                $index = strtolower($param);
                if (isset($this->params[ $index ])) {
                        return $this->params[ $index ];
                }
        }

        /**
         * Disabled ArrayAccess requirement
         * @see ArrayAccess.offsetSet
         */
        #[\ReturnTypeWillChange]
        public function offsetSet( $param, $val ):bool {
                return false;
        }

        /**
         * Disabled ArrayAccess requirement
         * @see ArrayAccess.offsetUnset
         */
        #[\ReturnTypeWillChange]
        public function offsetUnset( $param ):bool {
                return false;
        }

        /**
         * toString method.
         * @see getData()
         */
        public function __toString() {
                return $this->getData();
        }

        /**
         * @see Countable.count
         */
        #[\ReturnTypeWillChange]
        public function count():int {
                return count($this->params);
        }

        /**
         * @see IteratorAggregate.getIterator
         */
        #[\ReturnTypeWillChange]
        public function getIterator(): Traversable {
                return new ArrayIterator($this->params);
        }
}
