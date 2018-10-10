<?php

namespace EasySlug;

use Illuminate\Support\Str;

class EasySlug {

  public function VerifySlugs($value, $slug_field, $source = null) {

    $primaryKey = static::getKeyName();

    if (empty($value)) {
      if ($this->{$source}) {
        $value = Str::slug($this->{$source});
      } elseif ($this->title) {
        $value = Str::slug($this->title);
      } elseif ($this->name) {
        $value = Str::slug($this->name);
      } else {
        $value = 'slug_'. date("Y-m-d-H-i-s");
      }
    } else {
      $value = Str::slug($value);
    }

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
