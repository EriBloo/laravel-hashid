<?php

namespace Veelasky\LaravelHashId\Contracts;

/**
 * @method ?self byHash()
 */
interface HashesId {
    /**
     * Get Model by hash.
     *
     * @param string $hash
     *
     * @return self|null
     */
    public static function byHash(string $hash): ?self;

    /**
     * Get model by hash or fail.
     *
     * @param string $hash
     *
     * @return self
     *
     * @throw \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public static function byHashOrFail(string $hash): self;

    /**
     * Get Hash Attribute.
     *
     * @return string|null
     */
    public function getHashAttribute(): ?string;

    /**
     * Decode Hash to ID for the model.
     *
     * @param string $hash
     *
     * @return int|null
     */
    public static function hashToId(string $hash): ?int;

    /**
     * Get Hash Key.
     *
     * @return string
     */
    public function getHashKey(): string;

    /**
     * Encode Id to Hash for the model.
     *
     * @param int $primaryKey
     *
     * @return string
     */
    public static function idToHash(int $primaryKey): string;

    /**
     * Determine if hash should persist in database.
     *
     * @return bool
     */
    public function shouldHashPersist(): bool;

    /**
     * Get HashId column name.
     *
     * @return string
     */
    public function getHashColumnName(): string;
}