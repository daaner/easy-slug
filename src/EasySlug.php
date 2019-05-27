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
      if (static::where($slug_field, $value)->where($primaryKey, '!=', $this->getKey())->count()) {
        $x = 1;
        while (static::where($slug_field, $value . '-' . $x)->where($primaryKey, '!=', $this->getKey())->count()) {
          $x++;
        }
        $value .= '-'. $x;
      }
    } else {
      //new
      if (static::where($slug_field, $value)->count()) {
        $x = 1;
        while (static::where($slug_field, $value . '-' . $x)->count()) {
          $x++;
        }
        $value .= '-'. $x;
      }
    }

    return $this->attributes[$slug_field] = $value;
  }

}
