<?php

namespace EasySlug;

use Illuminate\Support\Str;

trait EasySlug {

  public function EasySlugCheck($value, $slug_field, $source = null) {

    $primaryKey = static::getKeyName();

    if (empty($value)) {
      if (isset($source)) {
        if(isset($this->{$source}) && $this->{$source}) {
          $value = Str::slug($this->{$source});
        } else {
          $value = 'slug_'. date("Y-m-d_H-i-s");
        }
      } elseif ($this->title) {
        $value = Str::slug($this->title);
      } elseif ($this->name) {
        $value = Str::slug($this->name);
      } else {
        $value = 'slug_'. date("Y-m-d_H-i-s");
      }
    } else {
      $value = Str::slug($value);
    }

    $value = mb_strimwidth($value, 0, 100);

    //check for unique
    if($this->getKey()) {
      //existing
      try {
        $existing = static::where($slug_field, $value)->where($primaryKey, '!=', $this->getKey())->withTrashed()->count();
      } catch (\Exception $e) {
        $existing = static::where($slug_field, $value)->where($primaryKey, '!=', $this->getKey())->count();
      }

      if ($existing) {
        $x = 1;
        try {
          $existing_while = static::where($slug_field, $value . '-' . $x)->where($primaryKey, '!=', $this->getKey())->withTrashed()->count();
        } catch (\Exception $e) {
          $existing_while = static::where($slug_field, $value . '-' . $x)->where($primaryKey, '!=', $this->getKey())->count();
        }
        while ($existing_while) {
          $x++;
        }
        $value .= '-'. $x;
      }
    } else {
      //new
      try {
        $new = static::where($slug_field, $value)->withTrashed()->count();
      } catch (\Exception $e) {
        $new = static::where($slug_field, $value)->count();
      }
      if ($new) {
        $x = 1;
        try {
          $new_while = static::where($slug_field, $value . '-' . $x)->withTrashed()->count();
        } catch (\Exception $e) {
          $new_while = static::where($slug_field, $value . '-' . $x)->count();
        }
        while ($new_while) {
          $x++;
        }
        $value .= '-'. $x;
      }
    }

    return $this->attributes[$slug_field] = $value;
  }

}
