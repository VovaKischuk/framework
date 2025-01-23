<?php

namespace Framework\Http;

use Psr\Http\Message\StreamInterface;
use RuntimeException;

class Stream implements StreamInterface
{
    private bool $seekable;
    private bool $readable;
    private bool $writable;

    public function __construct(private mixed $stream)
    {
        $meta = \stream_get_meta_data($stream);
        $this->seekable = $meta['seekable'];
        $this->readable = \str_contains($meta['mode'], 'r') || \str_contains($meta['mode'], '+');
        $this->writable = \str_contains($meta['mode'], 'w') || \str_contains($meta['mode'], '+');
    }

    public function __toString(): string
    {
        if (!isset($this->stream)) {
            return '';
        }

        $this->rewind();
        return $this->getContents();
    }

    public function close(): void
    {
        if (isset($this->stream)) {
            \fclose($this->stream);
            $this->stream = null;
        }
    }

    public function detach()
    {
        if (!isset($this->stream)) {
            return null;
        }

        $detachStream = $this->stream;
        $this->stream = null;
        $this->seekable = false;
        $this->readable = false;
        $this->writable = false;

        return $detachStream;
    }

    public function getSize(): ?int
    {
        if (!isset($this->stream)) {
            return null;
        }

        $stats = fstat($this->stream);
        return $stats['size'] ?? null;
    }

    public function tell(): int
    {
        $result = \ftell($this->stream);
        if ($result === false) {
            throw new RuntimeException('Unable to determine the position of the pointer in the stream');
        }
        return $result;
    }

    public function eof(): bool
    {
        return !$this->stream || feof($this->stream);
    }

    public function isSeekable(): bool
    {
        return $this->seekable;
    }

    public function seek($offset, $whence = SEEK_SET): void
    {
        if (!$this->seekable) {
            throw new RuntimeException('Stream is not seekable');
        }
        if (\fseek($this->stream, $offset, $whence) === -1) {
            throw new RuntimeException('Seeking position in the stream failed');
        }
    }

    public function rewind(): void
    {
        $this->seek(0);
    }

    public function isWritable(): bool
    {
        return $this->writable;
    }

    public function write($string): int
    {
        if (!$this->writable) {
            throw new RuntimeException('Cannot write to a non-writable stream');
        }
        $result = \fwrite($this->stream, $string);
        if ($result === false) {
            throw new RuntimeException('Failed to write to the stream');
        }

        return $result;
    }

    public function isReadable(): bool
    {
        return $this->readable;
    }

    public function read($length): string
    {
        if (!$this->readable) {
            throw new RuntimeException('Cannot read from a non-readable stream');
        }
        $result = \fread($this->stream, $length);
        if ($result === false) {
            throw new RuntimeException('Failed to read from the stream');
        }

        return $result;
    }

    public function getContents(): string
    {
        if (!isset($this->stream)) {
            throw new RuntimeException('Stream is detached');
        }
        $contents = \stream_get_contents($this->stream);
        if ($contents === false) {
            throw new RuntimeException('Failed to get contents from the stream');
        }

        return $contents;
    }

    public function getMetadata($key = null)
    {
        if (!isset($this->stream)) {
            return null;
        }
        $meta = \stream_get_meta_data($this->stream);
        if (\is_null($key)) {
            return $meta;
        }

        return $meta[$key] ?? null;
    }
}
