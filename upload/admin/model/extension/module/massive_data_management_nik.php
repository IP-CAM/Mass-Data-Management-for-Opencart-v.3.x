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

    public function editProductLinks($product_id, $data) {

        if (isset($data['manufacturer_id']) && $data['manufacturer_id'] != NULL) {
            $this->db->query("UPDATE " . DB_PREFIX . "product SET manufacturer_id = '" . (int)$data['manufacturer_id'] . "', date_modified = NOW() WHERE product_id = '" . (int)$product_id . "'");
        }

        if (isset($data['product_category']) && !empty($data['product_category'])) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");

            foreach ($data['product_category'] as $category_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
            }
        }

        if (isset($data['product_filter']) && !empty($data['product_filter'])) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");

            foreach ($data['product_filter'] as $filter_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_filter SET product_id = '" . (int)$product_id . "', filter_id = '" . (int)$filter_id . "'");
            }
        }

        if (isset($data['product_store']) && !empty($data['product_store'])) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");

            foreach ($data['product_store'] as $store_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
            }
        }

        if (isset($data['product_download']) && !empty($data['product_download'])) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");

            foreach ($data['product_download'] as $download_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int)$product_id . "', download_id = '" . (int)$download_id . "'");
            }
        }
    }

    public function editProductAttribute($product_id, $data) {
        if (isset($data['product_attribute']) && !empty($data['product_attribute'])) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");

            foreach ($data['product_attribute'] as $product_attribute) {
                if ($product_attribute['attribute_id']) {
                    // Removes duplicates
                    $this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");

                    foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {
                        $this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($product_attribute_description['text']) . "'");
                    }
                }
            }
        }
    }

    public function editProductOption($product_id, $data) {
        if (isset($data['product_option']) && !empty($data['product_option'])) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
            $this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "'");

            foreach ($data['product_option'] as $product_option) {
                if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
                    if (isset($product_option['product_option_value'])) {
                        $this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_option_id = '" . (int)$product_option['product_option_id'] . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");

                        $product_option_id = $this->db->getLastId();

                        foreach ($product_option['product_option_value'] as $product_option_value) {
                            $this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET product_option_value_id = '" . (int)$product_option_value['product_option_value_id'] . "', product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . (int)$product_option_value['option_value_id'] . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "', price = '" . (float)$product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (int)$product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float)$product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "'");
                        }
                    }
                } else {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_option_id = '" . (int)$product_option['product_option_id'] . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', value = '" . $this->db->escape($product_option['value']) . "', required = '" . (int)$product_option['required'] . "'");
                }
            }
        }
    }

    public function editProductDiscount($product_id, $data) {
        if (isset($data['product_discount']) && !empty($data['product_discount'])) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "'");

            foreach ($data['product_discount'] as $product_discount) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_discount['customer_group_id'] . "', quantity = '" . (int)$product_discount['quantity'] . "', priority = '" . (int)$product_discount['priority'] . "', price = '" . (float)$product_discount['price'] . "', date_start = '" . $this->db->escape($product_discount['date_start']) . "', date_end = '" . $this->db->escape($product_discount['date_end']) . "'");
            }
        }
    }

    public function editProductSpecial($product_id, $data) {
        if (isset($data['product_special']) && !empty($data['product_special'])) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");

            foreach ($data['product_special'] as $product_special) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_special['customer_group_id'] . "', priority = '" . (int)$product_special['priority'] . "', price = '" . (float)$product_special['price'] . "', date_start = '" . $this->db->escape($product_special['date_start']) . "', date_end = '" . $this->db->escape($product_special['date_end']) . "'");
            }
        }
    }

    public function editProductReward($product_id, $data) {
        if (isset($data['points']) && $data['points'] != NULL) {
            $this->db->query("UPDATE " . DB_PREFIX . "product SET points = '" . (int)$data['points'] . "', date_modified = NOW() WHERE product_id = '" . (int)$product_id . "'");
        }

        if (isset($data['product_reward']) && !empty($data['product_reward'])) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");

            foreach ($data['product_reward'] as $customer_group_id => $value) {
                if ((int)$value['points'] > 0) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "product_reward SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$customer_group_id . "', points = '" . (int)$value['points'] . "'");
                }
            }
        }
    }

    public function editProductSeo($product_id) {
        $query = $this->db->query("SELECT DISTINCT pd.name FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
        $product_name = $query->row['name'];

        $seo_product_name = $this->path_translit($product_name);

        $stores_data = $this->getStores();
        $languages_data = $this->getLanguages();

        $this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'product_id=" . (int)$product_id . "'");

        foreach ($languages_data as $language_code => $language_data) {
            if ($language_code == 'ru-ru') {
                $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)0 . "', language_id = '" . (int)$language_data['language_id'] . "', query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($seo_product_name) . "'");
            } else {
                $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)0 . "', language_id = '" . (int)$language_data['language_id'] . "', query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($seo_product_name . '-' . $language_code) . "'");
            }
        }

        foreach ($stores_data as $store_data) {
            foreach ($languages_data as $language_code => $language_data) {
                if ($language_code == 'ru-ru') {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_data['store_id'] . "', language_id = '" . (int)$language_data['language_id'] . "', query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($seo_product_name) . "'");
                } else {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_data['store_id'] . "', language_id = '" . (int)$language_data['language_id'] . "', query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($seo_product_name . '-' . $language_code) . "'");
                }
            }
        }
    }

    public function editProductUnfilledSeo($product_id) {
        $query = $this->db->query("SELECT DISTINCT pd.name FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
        $product_name = $query->row['name'];

        $seo_product_name = $this->path_translit($product_name);

        $stores_data = $this->getStores();
        $languages_data = $this->getLanguages();

        foreach ($languages_data as $language_code => $language_data) {
            $query = $this->db->query("SELECT keyword FROM " . DB_PREFIX . "seo_url WHERE store_id = '" . (int)0 . "' AND language_id = '" . (int)$language_data['language_id'] . "' AND query = 'product_id=" . (int)$product_id . "'");
            if (empty($query->row)) {
                if ($language_code == 'ru-ru') {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)0 . "', language_id = '" . (int)$language_data['language_id'] . "', query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($seo_product_name) . "'");
                } else {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)0 . "', language_id = '" . (int)$language_data['language_id'] . "', query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($seo_product_name . '-' . $language_code) . "'");
                }
            }
        }

        foreach ($stores_data as $store_data) {
            foreach ($languages_data as $language_code => $language_data) {
                $query = $this->db->query("SELECT keyword FROM " . DB_PREFIX . "seo_url WHERE store_id = '" . (int)$store_data['store_id'] . "' AND language_id = '" . (int)$language_data['language_id'] . "' AND query = 'product_id=" . (int)$product_id . "'");
                if (empty($query->row)) {
                    if ($language_code == 'ru-ru') {
                        $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_data['store_id'] . "', language_id = '" . (int)$language_data['language_id'] . "', query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($seo_product_name) . "'");
                    } else {
                        $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_data['store_id'] . "', language_id = '" . (int)$language_data['language_id'] . "', query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($seo_product_name . '-' . $language_code) . "'");
                    }
                }
            }
        }
    }

    private function getStores($data = array()) {
        $store_data = $this->cache->get('store');

        if (!$store_data) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store ORDER BY url");

            $store_data = $query->rows;

            $this->cache->set('store', $store_data);
        }

        return $store_data;
    }

    private function getLanguages($data = array()) {
        if ($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "language";

            $sort_data = array(
                'name',
                'code',
                'sort_order'
            );

            if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
                $sql .= " ORDER BY " . $data['sort'];
            } else {
                $sql .= " ORDER BY sort_order, name";
            }

            if (isset($data['order']) && ($data['order'] == 'DESC')) {
                $sql .= " DESC";
            } else {
                $sql .= " ASC";
            }

            if (isset($data['start']) || isset($data['limit'])) {
                if ($data['start'] < 0) {
                    $data['start'] = 0;
                }

                if ($data['limit'] < 1) {
                    $data['limit'] = 20;
                }

                $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
            }

            $query = $this->db->query($sql);

            return $query->rows;
        } else {
            $language_data = $this->cache->get('language');

            if (!$language_data) {
                $language_data = array();

                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language ORDER BY sort_order, name");

                foreach ($query->rows as $result) {
                    $language_data[$result['code']] = array(
                        'language_id' => $result['language_id'],
                        'name'        => $result['name'],
                        'code'        => $result['code'],
                        'locale'      => $result['locale'],
                        'image'       => $result['image'],
                        'directory'   => $result['directory'],
                        'sort_order'  => $result['sort_order'],
                        'status'      => $result['status']
                    );
                }

                $this->cache->set('admin.language', $language_data);
            }

            return $language_data;
        }
    }

    private function path_translit($value) {
        $converter = array(
            'а' => 'a',    'б' => 'b',    'в' => 'v',    'г' => 'g',    'д' => 'd',
            'е' => 'e',    'ё' => 'e',    'ж' => 'zh',   'з' => 'z',    'и' => 'i',
            'й' => 'y',    'к' => 'k',    'л' => 'l',    'м' => 'm',    'н' => 'n',
            'о' => 'o',    'п' => 'p',    'р' => 'r',    'с' => 's',    'т' => 't',
            'у' => 'u',    'ф' => 'f',    'х' => 'h',    'ц' => 'c',    'ч' => 'ch',
            'ш' => 'sh',   'щ' => 'sch',  'ь' => '',     'ы' => 'y',    'ъ' => '',
            'э' => 'e',    'ю' => 'yu',   'я' => 'ya',
        );

        $value = mb_strtolower($value);
        $value = strtr($value, $converter);
        $value = mb_ereg_replace('[^-0-9a-z]', '-', $value);
        $value = mb_ereg_replace('[-]+', '-', $value);
        $value = trim($value, '-');

        return $value;
    }

    public function editProductDesign($product_id, $data) {
        if (isset($data['product_layout']) && !empty($data['product_layout'])) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");

            foreach ($data['product_layout'] as $store_id => $layout_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_layout SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
            }
        }
    }

    public function getProductPrice($product_id) {
        $query = $this->db->query("SELECT `price` FROM " . DB_PREFIX ."product WHERE `product_id` = '" . $product_id . "'");

        return $query->row['price'];
    }

    public function editCategoryData($category_id, $data) {
        $sql = "UPDATE " . DB_PREFIX . "category SET";

        if (isset($data['parent_id']) && $data['parent_id'] != NULL && $data['parent_id'] != '-1' && (int)$data['parent_id'] != (int)$category_id) {
            $sql .= " parent_id = '" . (int)$data['parent_id'] . "',";
        }

        if (isset($data['top']) && $data['top'] != NULL && $data['top'] != '-1') {
            $sql .= " `top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "',";
        }

        if (isset($data['column']) && $data['column'] != NULL && $data['column'] != '-1') {
            $sql .= " `column` = '" . (int)$data['column'] . "',";
        }

        if (isset($data['sort_order']) && $data['sort_order'] != NULL && $data['sort_order'] != '-1') {
            $sql .= " sort_order = '" . (int)$data['sort_order'] . "',";
        }

        if (isset($data['image']) && $data['image'] != NULL && $data['image'] != '-1') {
            $sql .= " image = '" . $this->db->escape($data['image']) . "',";
        }

        if (isset($data['status']) && $data['status'] != NULL && $data['status'] != '-1') {
            $sql .= " status = '" . (int)$data['status'] . "',";
        }

        $sql .= " date_modified = NOW()";

        $sql .= " WHERE category_id = '" . (int)$category_id . "'";

        $this->db->query($sql);

        if (isset($data['parent_id']) && $data['parent_id'] != NULL && $data['parent_id'] != '-1' && (int)$data['parent_id'] != (int)$category_id) {

            // MySQL Hierarchical Data Closure Table Pattern
            $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE path_id = '" . (int)$category_id . "' ORDER BY level ASC");

            if ($query->rows) {
                foreach ($query->rows as $category_path) {
                    // Delete the path below the current one
                    $this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category_path['category_id'] . "' AND level < '" . (int)$category_path['level'] . "'");

                    $path = array();

                    // Get the nodes new parents
                    $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");

                    foreach ($query->rows as $result) {
                        $path[] = $result['path_id'];
                    }

                    // Get whats left of the nodes current path
                    $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category_path['category_id'] . "' ORDER BY level ASC");

                    foreach ($query->rows as $result) {
                        $path[] = $result['path_id'];
                    }

                    // Combine the paths with a new level
                    $level = 0;

                    foreach ($path as $path_id) {
                        $this->db->query("REPLACE INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category_path['category_id'] . "', `path_id` = '" . (int)$path_id . "', level = '" . (int)$level . "'");

                        $level++;
                    }
                }
            } else {
                // Delete the path below the current one
                $this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category_id . "'");

                // Fix for records with no paths
                $level = 0;

                $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");

                foreach ($query->rows as $result) {
                    $this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category_id . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");

                    $level++;
                }

                $this->db->query("REPLACE INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category_id . "', `path_id` = '" . (int)$category_id . "', level = '" . (int)$level . "'");
            }

        }

        if (isset($data['category_filter'])) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "category_filter WHERE category_id = '" . (int)$category_id . "'");

            foreach ($data['category_filter'] as $filter_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "category_filter SET category_id = '" . (int)$category_id . "', filter_id = '" . (int)$filter_id . "'");
            }
        }

        if (isset($data['category_store'])) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "category_to_store WHERE category_id = '" . (int)$category_id . "'");

            foreach ($data['category_store'] as $store_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "category_to_store SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "'");
            }
        }
    }

    public function editCategorySeo($category_id) {
        $query = $this->db->query("SELECT DISTINCT *, (SELECT cd2.name as name FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id AND cp.category_id != cp.path_id) WHERE cp.category_id = c.category_id AND cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY cp.category_id) AS path FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (c.category_id = cd2.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'");

        $category_name = $query->row['name'];

        $seo_category_name = $this->path_translit($category_name);

        $stores_data = $this->getStores();
        $languages_data = $this->getLanguages();

        $this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE query = 'category_id=" . (int)$category_id . "'");

        foreach ($languages_data as $language_code => $language_data) {
            if ($language_code == 'ru-ru') {
                $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)0 . "', language_id = '" . (int)$language_data['language_id'] . "', query = 'category_id=" . (int)$category_id . "', keyword = '" . $this->db->escape($seo_category_name) . "'");
            } else {
                $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)0 . "', language_id = '" . (int)$language_data['language_id'] . "', query = 'category_id=" . (int)$category_id . "', keyword = '" . $this->db->escape($seo_category_name . '-' . $language_code) . "'");
            }
        }

        foreach ($stores_data as $store_data) {
            foreach ($languages_data as $language_code => $language_data) {
                if ($language_code == 'ru-ru') {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_data['store_id'] . "', language_id = '" . (int)$language_data['language_id'] . "', query = 'category_id=" . (int)$category_id . "', keyword = '" . $this->db->escape($seo_category_name) . "'");
                } else {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_data['store_id'] . "', language_id = '" . (int)$language_data['language_id'] . "', query = 'category_id=" . (int)$category_id . "', keyword = '" . $this->db->escape($seo_category_name . '-' . $language_code) . "'");
                }
            }
        }
    }

    public function editCategoryUnfilledSeo($category_id) {
        $query = $this->db->query("SELECT DISTINCT *, (SELECT cd2.name as name FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id AND cp.category_id != cp.path_id) WHERE cp.category_id = c.category_id AND cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY cp.category_id) AS path FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (c.category_id = cd2.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'");

        $category_name = $query->row['name'];

        $seo_category_name = $this->path_translit($category_name);

        $stores_data = $this->getStores();
        $languages_data = $this->getLanguages();

        foreach ($languages_data as $language_code => $language_data) {
            $query = $this->db->query("SELECT keyword FROM " . DB_PREFIX . "seo_url WHERE store_id = '" . (int)0 . "' AND language_id = '" . (int)$language_data['language_id'] . "' AND query = 'category_id=" . (int)$category_id . "'");
            if (empty($query->row)) {
                if ($language_code == 'ru-ru') {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)0 . "', language_id = '" . (int)$language_data['language_id'] . "', query = 'category_id=" . (int)$category_id . "', keyword = '" . $this->db->escape($seo_category_name) . "'");
                } else {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)0 . "', language_id = '" . (int)$language_data['language_id'] . "', query = 'category_id=" . (int)$category_id . "', keyword = '" . $this->db->escape($seo_category_name . '-' . $language_code) . "'");
                }
            }
        }

        foreach ($stores_data as $store_data) {
            foreach ($languages_data as $language_code => $language_data) {
                $query = $this->db->query("SELECT keyword FROM " . DB_PREFIX . "seo_url WHERE store_id = '" . (int)$store_data['store_id'] . "' AND language_id = '" . (int)$language_data['language_id'] . "' AND query = 'category_id=" . (int)$category_id . "'");
                if (empty($query->row)) {
                    if ($language_code == 'ru-ru') {
                        $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_data['store_id'] . "', language_id = '" . (int)$language_data['language_id'] . "', query = 'category_id=" . (int)$category_id . "', keyword = '" . $this->db->escape($seo_category_name) . "'");
                    } else {
                        $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_data['store_id'] . "', language_id = '" . (int)$language_data['language_id'] . "', query = 'category_id=" . (int)$category_id . "', keyword = '" . $this->db->escape($seo_category_name . '-' . $language_code) . "'");
                    }
                }
            }
        }
    }

    public function editCategoryDesign($category_id, $data) {
        if (isset($data['category_layout'])) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int)$category_id . "'");

            foreach ($data['category_layout'] as $store_id => $layout_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "category_to_layout SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
            }
        }
    }

    public function getCategories($data = array()) {
        $sql = "SELECT cp.category_id AS category_id, cd2.name AS name, c1.parent_id, c1.sort_order FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category c1 ON (cp.category_id = c1.category_id) LEFT JOIN " . DB_PREFIX . "category c2 ON (cp.path_id = c2.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (cp.category_id = cd2.category_id) WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        if (!empty($data['filter_name'])) {
            $sql .= " AND cd2.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        $sql .= " GROUP BY cp.category_id";

        $sort_data = array(
            'name',
            'sort_order'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY sort_order";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function editInformationData($information_id, $data) {

        if ( (isset($data['bottom']) && $data['bottom'] != NULL && $data['bottom'] != '-1') || (isset($data['status']) && $data['status'] != NULL && $data['status'] != '-1') ) {
            $sql = "UPDATE " . DB_PREFIX . "information SET";

            if (isset($data['bottom']) && $data['bottom'] != NULL && $data['bottom'] != '-1') {
                $sql .= " bottom = '" . (int)$data['bottom'] . "'";
            }

            if ( (isset($data['bottom']) && $data['bottom'] != NULL && $data['bottom'] != '-1') && (isset($data['status']) && $data['status'] != NULL && $data['status'] != '-1') ) {
                $sql .= ",";
            }

            if (isset($data['status']) && $data['status'] != NULL && $data['status'] != '-1') {
                $sql .= " status = '" . (int)$data['status'] . "'";
            }

            $sql .= " WHERE information_id = '" . (int)$information_id . "'";

            $this->db->query($sql);
        }

        if (isset($data['information_store'])) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "information_to_store WHERE information_id = '" . (int)$information_id . "'");

            foreach ($data['information_store'] as $store_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "information_to_store SET information_id = '" . (int)$information_id . "', store_id = '" . (int)$store_id . "'");
            }
        }
    }

    public function editInformationSeo($information_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information_description WHERE information_id = '" . (int)$information_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

        $information_title = $query->row['title'];

        $seo_information_title = $this->path_translit($information_title);

        $stores_data = $this->getStores();
        $languages_data = $this->getLanguages();

        $this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'information_id=" . (int)$information_id . "'");

        foreach ($languages_data as $language_code => $language_data) {
            if ($language_code == 'ru-ru') {
                $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)0 . "', language_id = '" . (int)$language_data['language_id'] . "', query = 'information_id=" . (int)$information_id . "', keyword = '" . $this->db->escape($seo_information_title) . "'");
            } else {
                $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)0 . "', language_id = '" . (int)$language_data['language_id'] . "', query = 'information_id=" . (int)$information_id . "', keyword = '" . $this->db->escape($seo_information_title . '-' . $language_code) . "'");
            }
        }

        foreach ($stores_data as $store_data) {
            foreach ($languages_data as $language_code => $language_data) {
                if ($language_code == 'ru-ru') {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_data['store_id'] . "', language_id = '" . (int)$language_data['language_id'] . "', query = 'information_id=" . (int)$information_id . "', keyword = '" . $this->db->escape($seo_information_title) . "'");
                } else {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_data['store_id'] . "', language_id = '" . (int)$language_data['language_id'] . "', query = 'information_id=" . (int)$information_id . "', keyword = '" . $this->db->escape($seo_information_title . '-' . $language_code) . "'");
                }
            }
        }
    }

    public function editInformationUnfilledSeo($information_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information_description WHERE information_id = '" . (int)$information_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

        $information_title = $query->row['title'];

        $seo_information_title = $this->path_translit($information_title);

        $stores_data = $this->getStores();
        $languages_data = $this->getLanguages();

        foreach ($languages_data as $language_code => $language_data) {
            $query = $this->db->query("SELECT keyword FROM " . DB_PREFIX . "seo_url WHERE store_id = '" . (int)0 . "' AND language_id = '" . (int)$language_data['language_id'] . "' AND query = 'information_id=" . (int)$information_id . "'");
            if (empty($query->row)) {
                if ($language_code == 'ru-ru') {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)0 . "', language_id = '" . (int)$language_data['language_id'] . "', query = 'information_id=" . (int)$information_id . "', keyword = '" . $this->db->escape($seo_information_title) . "'");
                } else {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)0 . "', language_id = '" . (int)$language_data['language_id'] . "', query = 'information_id=" . (int)$information_id . "', keyword = '" . $this->db->escape($seo_information_title . '-' . $language_code) . "'");
                }
            }
        }

        foreach ($stores_data as $store_data) {
            foreach ($languages_data as $language_code => $language_data) {
                $query = $this->db->query("SELECT keyword FROM " . DB_PREFIX . "seo_url WHERE store_id = '" . (int)$store_data['store_id'] . "' AND language_id = '" . (int)$language_data['language_id'] . "' AND query = 'information_id=" . (int)$information_id . "'");
                if (empty($query->row)) {
                    if ($language_code == 'ru-ru') {
                        $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_data['store_id'] . "', language_id = '" . (int)$language_data['language_id'] . "', query = 'information_id=" . (int)$information_id . "', keyword = '" . $this->db->escape($seo_information_title) . "'");
                    } else {
                        $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_data['store_id'] . "', language_id = '" . (int)$language_data['language_id'] . "', query = 'information_id=" . (int)$information_id . "', keyword = '" . $this->db->escape($seo_information_title . '-' . $language_code) . "'");
                    }
                }
            }
        }
    }

    public function editInformationDesign($information_id, $data) {
        if (isset($data['information_layout'])) {
            $this->db->query("DELETE FROM `" . DB_PREFIX . "information_to_layout` WHERE information_id = '" . (int)$information_id . "'");

            foreach ($data['information_layout'] as $store_id => $layout_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "information_to_layout SET information_id = '" . (int)$information_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
            }
        }
    }

    public function getInformations($data = array()) {
        $sql = "SELECT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        if (!empty($data['filter_name'])) {
            $sql .= " AND id.title LIKE '" . $this->db->escape($data['filter_name']) . "%'";
        }

        $sort_data = array(
            'id.title',
            'i.sort_order'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY id.title";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getAllInformationsDescription() {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

        return $query->rows;
    }
}