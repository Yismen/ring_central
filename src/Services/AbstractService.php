<?php

namespace Dainsys\RingCentral\Services;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

abstract class AbstractService
{
    protected array $fields;
    protected Carbon $date_from;
    protected Carbon $date_to;
    protected Builder $query;
    protected bool $group_by_date = true;

    // API: (new Service)->datesBetween(date_from, date_to)->groupByDate()->build()->get();

    public function __construct()
    {
        $this->date_from = now();
        $this->date_to = now();
        $this->query = $this->baseQuery();
    }

    abstract protected function baseQuery(): Builder;

    public function datesBetween(array $dates): self
    {
        $this->date_from = Carbon::parse($dates[0]);
        $this->date_to = Carbon::parse($dates[1] ?? $dates[0]);

        return $this;
    }

    public function groupByDate(): self
    {
        $this->group_by_date = true;

        return $this;
    }

    public function withoutDateGrouping(): self
    {
        $this->group_by_date = false;

        return $this;
    }

    public function build(array $fields): Builder
    {
        $this->query->where(function ($q) {
            $q->whereDate('date', '>=', $this->date_from)
            ->whereDate('date', '<=', $this->date_to);
        });

        if ($this->group_by_date) {
            $this->query
                ->addSelect('date')
                ->groupBy('date')
                ->orderBy('date')
            ;
        } else {
            $this->query
                ->selectRaw('MIN(date) AS date_from, MAX(date) AS date_to')
                ;
        }

        foreach ($fields as $field => $value) {
            $this->query->addSelect($field)
                ->groupBy($field)
                ->orderBy($field);

            $this->query->where(function ($q) use ($field, $value) {
                if (is_array($value)) {
                    foreach ($value as $string) {
                        $q->orWhere($field, 'like', $string);
                    }
                } else {
                    $q->where($field, 'like', $value);
                }
            });
        }

        return $this->query;
    }
}
