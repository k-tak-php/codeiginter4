<?php

namespace App\Models;

use CodeIgniter\Model;

class ArticleDetail extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'article_details';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    // protected $insertID         = 0;
    protected $returnType       = 'array';

    // 論理削除定義（ここでは明示的にdeletedというintがたのカラムを指定）
    protected $useSoftDeletes   = true;
    protected $deletedField = 'deleted';

    protected $protectFields    = true;
    // allowedFieldsはsave, insert, updateメソッドで操作することができるフィールド
    protected $allowedFields    = [
        'article_id',
        'context'
    ];
    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
