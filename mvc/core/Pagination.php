<?php

class Pagination extends Controller
{
    public $paginationModel;
    public $queryModel;

    public function __construct($model)
    {
        $this->paginationModel = $this->model("PaginationModel");
        $this->queryModel = $this->model($model);
    }

    public function getData($args)
    {
        $limit = 10;
        $page = 1;
        $input = $input ?? null;
        $filter = $filter ?? null;
        extract($args);
        $offset = ($page - 1) * $limit;
        $query = $this->queryModel->getQuery($filter, $input, $args);
        $result = $this->paginationModel->pagination($query, $limit, $offset);
        echo json_encode($result);
    }

    public function getTotal($args)
    {
        $limit = 10;
        $input = $args['input'] ?? null;
        $filter = $args['filter'] ?? null;

        // Lấy query chính từ model
        $queryResult = $this->queryModel->getQuery($filter, $input, $args);

        $originalQuery = is_array($queryResult) ? $queryResult['query'] : $queryResult;
        $params = $queryResult['params'] ?? [];

        // Tách phần GROUP BY nếu có
        $groupByPos = stripos($originalQuery, 'GROUP BY');
        $mainQuery = $groupByPos !== false ? substr($originalQuery, 0, $groupByPos) : $originalQuery;

        // Tạo câu truy vấn đếm (giả sử bảng cần đếm là bảng chính đầu tiên)
        $count_query = preg_replace('/SELECT\s.+?\sFROM/i', 'SELECT COUNT(*) AS total FROM', $mainQuery);

        // Nếu có GROUP BY → cần đếm số hàng sau khi nhóm
        if ($groupByPos !== false) {
            $count_query = "SELECT COUNT(*) AS total FROM ( $originalQuery ) AS grouped_result";
        }

        $queryData = [
            'query' => $originalQuery,
            'params' => $params,
            'count_query' => $count_query
        ];

        $result = $this->paginationModel->getTotalPages($queryData, $limit, $args);
        echo $result;
    }

}
