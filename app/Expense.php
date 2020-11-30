<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        "user_id", "expense_category_id", "expense_name", "nominal", "date"
    ];

    public function expenseCategory()
    {
        return $this->hasOne('App\ExpenseCategory', 'id', 'expense_category_id');
    }
}
