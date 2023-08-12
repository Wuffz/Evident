<?php

namespace Evident\Lingua\Traits;

trait BuildsQueries {
    private function buildQuery($query): string {
        
        $iam = fn($in) => in_array($in, class_uses($this));

        if ( $iam(Joinable::class) ) {
            if (!empty($this->joinClauses)) {
                $query .= ' ' .trim( implode(' ', $this->joinClauses),' ');
            }
        }

        if ( $iam(Whereable::class) ) {
            if (!empty($this->whereConditions)) {
                $query .= ' WHERE ' . implode(' AND ', $this->whereConditions);
            }
        }
        if ( $iam(Groupable::class) ) {
            if (!empty($this->groupByColumns)) {
                $query .= ' GROUP BY ' . implode(', ', $this->groupByColumns);
            }
        }

        if ( $iam(Orderable::class) ) {
            if (!empty($this->orderByColumns)) {
                $query .= ' ORDER BY ' . implode(', ', $this->orderByColumns);
            }
        }
        if ( $iam(Offsetable::class) ) {
            if ($this->offset !== null) {
                $query .= " OFFSET {$this->offset}";
            }
        }
        if ( $iam(Limitable::class) ) {
            if ($this->limit !== null) {
                $query .= " LIMIT {$this->limit}";
            }
        }

        return $query;
    }
}