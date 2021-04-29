<?php
class ModelExtensionModuleMassiveDataManagementNik extends Model {
    public function editProductData($product_id, $data, $price) {
        $sql = "UPDATE " . DB_PREFIX . "product SET";

        if (isset($data['location']) && $data['location'] != NULL && $data['location'] != '-1') {
            $sql .= " location = '" . $this->db->escape($data['location']) . "',";
        }

        if (isset($price) && $price != NULL && $price != '-1') {
            $sql .= " price = '" . (int)$price . "',";
        }

        if (isset($data['tax_class_id']) && $data['tax_class_id'] != NULL && $data['tax_class_id'] != '-1') {
            $sql .= " tax_class_id = '" . (int)$data['tax_class_id'] . "',";
        }

        if (isset($data['quantity']) && $data['quantity'] != NULL && $data['quantity'] != '-1') {
            $sql .= " quantity = '" . (int)$data['quantity'] . "',";
        }

        if (isset($data['minimum']) && $data['minimum'] != NULL && $data['minimum'] != '-1') {
            $sql .= " minimum = '" . (int)$data['minimum'] . "',";
        }

        if (isset($data['subtract']) && $data['subtract'] != NULL && $data['subtract'] != '-1') {
            $sql .= " subtract = '" . (int)$data['subtract'] . "',";
        }

        if (isset($data['stock_status_id']) && $data['stock_status_id'] != NULL && $data['stock_status_id'] != '-1') {
            $sql .= " stock_status_id = '" . (int)$data['stock_status_id'] . "',";
        }

        if (isset($data['shipping']) && $data['shipping'] != NULL && $data['shipping'] != '-1') {
            $sql .= " shipping = '" . (int)$data['shipping'] . "',";
        }

        if (isset($data['date_available']) && $data['date_available'] != NULL && $data['date_available'] != '-1') {
            $sql .= " date_available = '" . $this->db->escape($data['date_available']) . "',";
        }

        if (isset($data['length']) && $data['length'] != NULL && $data['length'] != '-1') {
            $sql .= " length = '" . (float)$data['length'] . "',";
        }

        if (isset($data['width']) && $data['width'] != NULL && $data['width'] != '-1') {
            $sql .= " width = '" . (float)$data['width'] . "',";
        }

        if (isset($data['height']) && $data['height'] != NULL && $data['height'] != '-1') {
            $sql .= " height = '" . (float)$data['height'] . "',";
        }

        if (isset($data['length_class_id']) && $data['length_class_id'] != NULL && $data['length_class_id'] != '-1') {
            $sql .= " length_class_id = '" . (int)$data['length_class_id'] . "',";
        }

        if (isset($data['weight']) && $data['weight'] != NULL && $data['weight'] != '-1') {
            $sql .= " weight = '" . (int)$data['weight'] . "',";
        }

        if (isset($data['weight_class_id']) && $data['weight_class_id'] != NULL && $data['weight_class_id'] != '-1') {
            $sql .= " weight_class_id = '" . (int)$data['weight_class_id'] . "',";
        }

        if (isset($data['status']) && $data['status'] != NULL && $data['status'] != '-1') {
            $sql .= " status = '" . (int)$data['status'] . "',";
        }

        $sql .= " date_modified = NOW()";

        $sql .= " WHERE product_id = '" . (int)$product_id . "'";

        $this->db->query($sql);
    }

    public function editProductLinks() {

    }

    public function getProductPrice($product_id) {
        $query = $this->db->query("SELECT `price` FROM " . DB_PREFIX ."product WHERE `product_id` = '" . $product_id . "'");

        return $query->row['price'];
    }
}