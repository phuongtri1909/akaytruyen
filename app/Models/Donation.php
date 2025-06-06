<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $table = 'donations'; // Tên bảng

    protected $fillable = ['name', 'amount', 'donated_at']; // Các cột có thể điền dữ liệu

    public $timestamps = true; // Cho phép sử dụng created_at & updated_at
}
