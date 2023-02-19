<?php

namespace App\Controllers\Sample;

use App\Controllers\BaseController;
use App\Models\Article as ArticleModel;
use App\Models\ArticleDetail;

// Traitを使うことでAPIレスポンス時のステータスコードを簡潔にかける
use CodeIgniter\API\ResponseTrait;

class Article extends BaseController
{
    use ResponseTrait;

    public function make()
    {
        // POST値から値を取得する
        $title = $this->request->getJSON(true)["title"];
        $context = $this->request->getJSON(true)["context"];

        /** @todo サービスクラスでの実装 / ビジネスロジックはController内に書かない */
        // articleテーブルとarticle_detailテーブルにインサートする
        $articleModel = new ArticleModel();
        $articleModel->insert(
            [
                "title" => $title
            ]
        );
        $inserted_article_id = $articleModel->getInsertID();

        $articleDetailModel = new ArticleDetail();
        $articleDetailModel->insert(
            [
                "article_id" => $inserted_article_id,
                "context" => $context
            ]
        );
        $inserted_article_detail_id = $articleDetailModel->getInsertID();

        /** @todo バリデーションとトランザクション処理 */
        $response = [
            "message" => "success",
            "data" => [
                "articles" => $inserted_article_id,
                "article_details" => $inserted_article_detail_id,
            ]
        ];

        /*
         作成成功のレスポンス
         自分でjson_encodeしなくても、respondCreatedの引数dataが配列の場合
         クライアントが要求した内容でコンテンツタイプをレスポンスする。デフォルトはjson。
         ここではjsonのみ返却するようConfig\Format.phpも変更済み。
        */
        return $this->respondCreated(data: $response);
    }

    public function find(int $id)
    {
        // GET値から値を取得する
        $articleModel = new ArticleModel();

        /** @todo バリデーションとModelでのjoin */
        $response = [
            "message" => "success",
            "data" => [$id]
        ];

        return $this->respondCreated(data: $response);
    }

    public function update()
    {
        /** @todo */
    }

    public function delete()
    {
        /** @todo */
    }
}
