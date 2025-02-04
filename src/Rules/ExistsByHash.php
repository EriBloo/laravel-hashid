<?php

namespace Veelasky\LaravelHashId\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Exists;
use Illuminate\Validation\Validator;
use Veelasky\LaravelHashId\Contracts\HashesId;

class ExistsByHash extends Exists implements ValidationRule, ValidatorAwareRule
{
    protected Model&HashesId $model;
    protected Validator $validator;

    /**
     * @param class-string<Model&HashesId> $class
     */
    public function __construct(string $class)
    {
        $this->model = new $class();

        parent::__construct($class, $this->model->shouldHashPersist() ? $this->model->getHashColumnName() : $this->model->getKeyName());
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$value || (!$this->model->shouldHashPersist() && !$value = $this->model::hashToId($value))) {
            $this->fail($attribute, $fail);

            return;
        }

        $validator = validator(
            [$attribute => $value],
            [$attribute => $this->buildParentRule()]
        );

        if ($validator->fails()) {
            $this->fail($attribute, $fail);
        }
    }

    public function setValidator($validator): static
    {
        $this->validator = $validator;

        return $this;
    }

    protected function buildParentRule(): Exists
    {
        return tap(new parent($this->table, $this->column), function (Exists $parent) {
            $parent->wheres = $this->wheres;
            $parent->using = $this->using;
        });
    }

    protected function fail(string $attribute, Closure $fail): void
    {
        $fail($this->validator->customMessages["{$attribute}.existsByHash"] ?? 'validation.exists')
            ->translate([
                'attribute' => $this->validator->getDisplayableAttribute($attribute),
            ]);
    }
}
