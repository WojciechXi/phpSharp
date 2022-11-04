<?php

namespace System\Collections {

    use Traversable;

    use Countable;
    use ArrayAccess;
    use ArrayIterator;
    use JsonSerializable;
    use IteratorAggregate;

    class gList implements IteratorAggregate, ArrayAccess, JsonSerializable, Countable {

        public function __construct(array | object $data = []) {
            $this->data = $data ? (array)$data : [];
        }

        private array $data = [];

        public function __get(string $propertyName) {
            if ($propertyName == 'Length') return $this->Length();
        }

        public function Prepend(mixed $value): int {
            return array_unshift($this->data, $value);
        }

        public function Append(mixed $value): int {
            return array_push($this->data, $value);
        }

        public function Add(mixed $value): int {
            return $this->Append($value);
        }

        public function RemoteAt(mixed $index): bool {
            if (is_null($index)) return false;
            $this->offsetUnset($index);
            return true;
        }

        public function Remove(mixed $value): bool {
            $index = $this->IndexOf($value);
            if ($index === false) return false;
            return $this->RemoteAt($index);
        }

        public function IndexOf(mixed $value): int {
            return array_search($this->data, $value, true);
        }

        public function Find(callable $where): mixed {
            foreach ($this->data as $key => $value)  if ($where($value, $key)) return $value;
            return null;
        }

        public function Where(callable $where): static {
            $newList = new gList();
            foreach ($this->data as $key => $value) if ($where($value, $key)) $newList->Add($value);
            return $newList;
        }

        public function Sort(callable $sort): self {
            usort($this->data, $sort);
            return $this;
        }

        public function Length(): int {
            return $this->count();
        }

        public function Clone(): static {
            return new gList($this->data);
        }

        public function ToArray(): array {
            return $this->data;
        }

        //IteratorAggregate

        public function getIterator(): Traversable {
            return new ArrayIterator($this);
        }

        //ArrayAccess

        public function offsetExists(mixed $index): bool {
            if (is_null($index)) return false;
            return isset($this->data[$index]);
        }

        public function offsetGet(mixed $index): mixed {
            if (is_null($index)) return null;
            return isset($this->data[$index]) ? $this->data[$index] : null;
        }

        public function offsetSet(mixed $index, mixed $value): void {
            if (is_null($index)) $this->data[] = $value;
            if ($this->offsetExists($index)) $this->data[$index] = $value;
        }

        public function offsetUnset(mixed $index): void {
            unset($this->data[$index]);
        }

        //JsonSerializable

        public function jsonSerialize(): mixed {
            return $this->data;
        }

        //Countable

        public function count(): int {
            return count($this->data);
        }
    }
}
