<?php

namespace Framework\Http;

use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\StreamInterface;
use RuntimeException;

class UploadedFile implements UploadedFileInterface
{
    private StreamInterface|null $stream = null;
    private bool $moved = false;

    public function __construct(
        private readonly string $file,
        private readonly int $size,
        private readonly int $error,
        private readonly ?string $clientFilename = null,
        private readonly ?string $clientMediaType = null
    ) {
    }

    public function getStream(): StreamInterface
    {
        if ($this->moved) {
            throw new RuntimeException('Cannot retrieve stream after it has been moved');
        }

        if ($this->stream === null) {
            $this->stream = new Stream(\fopen($this->file, 'r+'));
        }

        return $this->stream;
    }

    public function moveTo(string $targetPath): void
    {
        if ($this->moved) {
            throw new RuntimeException('The uploaded file has been moved');
        }

        if ($this->error !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Cannot move file due to upload error');
        }

        $this->validateTargetPath($targetPath);

        if (!\move_uploaded_file($this->file, $targetPath)) {
            throw new RuntimeException('Failed to move uploaded file.');
        }

        $this->moved = true;
    }

    public function getSize(): int|null
    {
        return $this->size;
    }

    public function getError(): int
    {
        return $this->error;
    }

    public function getClientFilename(): string|null
    {
        return $this->clientFilename;
    }

    public function getClientMediaType(): string|null
    {
        return $this->clientMediaType;
    }

    private function validateTargetPath(string $targetPath): void
    {
        $directory = \dirname($targetPath);
        if (!\is_writable($directory)) {
            throw new RuntimeException('Upload target path is not writable.');
        }
    }
}
