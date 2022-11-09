<?php

namespace Server\Request {

    class Params {

        public function __construct(RequestUri $from, RequestUri $to) {
            $this->from = $from;
            $this->to = $to;
        }

        private ?RequestUri $from = null;
        private ?RequestUri $to = null;

        public function Get(string $key, string $defaultValue = null): ?string {
            $length = $this->from->Length();
            for ($i = 0; $i < $length; $i++)
                if ($this->from->Get($i) == ":{$key}") return $this->to->Get($i);
            return $defaultValue;
        }
    }
}
