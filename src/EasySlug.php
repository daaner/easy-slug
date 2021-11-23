<?php

namespace EasySlug;

use Exception;
use Illuminate\Support\Str;

/**
 * @method static getKeyName()
 * @method static where($column, string $value)
 * @method getKey()
 */
trait EasySlug
{


    /**
     * @param $value
     * @param $slug_field
     * @param null $source
     * @return string
     */
    public function EasySlugCheck($value, $slug_field, $source = null)
    {
        $primaryKey = static::getKeyName();

        if (empty($value)) {

            if (isset($source)) {

                if (isset($this->{$source}) && $this->{$source}) {

                    $value = Str::slug($this->{$source});

                } else {

                    $value = 'slug_' . date("Y-m-d_H-i-s");

                }

            } elseif ($this->title) {

                $value = Str::slug($this->title);

            } elseif ($this->name) {

                $value = Str::slug($this->name);

            } else {

                $value = 'slug_' . date("Y-m-d_H-i-s");

            }

        } else {

            $value = Str::slug($value);

        }


        $value = mb_strimwidth($value, 0, 100);


        /**
         * Check for unique
         */
        if ($this->getKey()) {

            /** Existing value */
            try {

                $existing = static::where($slug_field, $value)->where($primaryKey, '!=',
                    $this->getKey())->withTrashed()->count();

            } catch (Exception $e) {

                $existing = static::where($slug_field, $value)->where($primaryKey, '!=', $this->getKey())->count();

            }


            if ($existing) {

                $x = 1;

                try {

                    $existing_while = static::where($slug_field, $value . '-' . $x)->where($primaryKey, '!=',
                        $this->getKey())->withTrashed()->count();

                } catch (Exception $e) {

                    $existing_while = static::where($slug_field, $value . '-' . $x)->where($primaryKey, '!=',
                        $this->getKey())->count();

                }


                while ($existing_while) {
                    $x++;
                }


                $value .= '-' . $x;
            }

        } else {

            /** New value */

            try {

                $new = static::where($slug_field, $value)->withTrashed()->count();

            } catch (Exception $e) {

                $new = static::where($slug_field, $value)->count();

            }

            if ($new) {

                $x = 1;

                try {

                    $new_while = static::where($slug_field, $value . '-' . $x)->withTrashed()->count();

                } catch (Exception $e) {

                    $new_while = static::where($slug_field, $value . '-' . $x)->count();

                }

                while ($new_while) {

                    $x++;

                }


                $value .= '-' . $x;
            }
        }


        return $this->attributes[$slug_field] = $value;
    }

}
