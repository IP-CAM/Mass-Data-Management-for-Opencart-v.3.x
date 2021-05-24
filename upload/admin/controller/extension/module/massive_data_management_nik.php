<?php
class ControllerExtensionModuleMassiveDataManagementNik extends Controller {
	private $error = array();

	public function index() {
        $this->load->language('catalog/product');
        $this->load->language('catalog/category');
		$this->load->language('extension/module/massive_data_management_nik');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
//			$this->model_setting_setting->editSetting('module_account', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

        if (isset($this->request->get['type'])) {
            $type = $this->request->get['type'];
        } else {
            $type = 'products';
        }

        $url = '';

        if (isset($this->request->get['type'])) {
            $url .= '&type=' . $type;
        }

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/massive_data_management_nik', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['actionProductData'] = $this->url->link('extension/module/massive_data_management_nik/productsData', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['actionProductLinks'] = $this->url->link('extension/module/massive_data_management_nik/productsLinks', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['actionProductAttr'] = $this->url->link('extension/module/massive_data_management_nik/productsAttribute', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['actionProductOption'] = $this->url->link('extension/module/massive_data_management_nik/productsOption', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['actionProductDiscount'] = $this->url->link('extension/module/massive_data_management_nik/productsDiscount', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['actionProductSpecial'] = $this->url->link('extension/module/massive_data_management_nik/productsSpecial', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['actionProductReward'] = $this->url->link('extension/module/massive_data_management_nik/productsReward', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['actionProductSeo'] = $this->url->link('extension/module/massive_data_management_nik/productsSeo', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['actionProductDesign'] = $this->url->link('extension/module/massive_data_management_nik/productsDesign', 'user_token=' . $this->session->data['user_token'] . $url, true);

        $data['actionCategoryData'] = $this->url->link('extension/module/massive_data_management_nik/categoriesData', 'user_token=' . $this->session->data['user_token'] . $url, true);
        $data['actionCategorySeo'] = $this->url->link('extension/module/massive_data_management_nik/categoriesSeo', 'user_token=' . $this->session->data['user_token'] . $url, true);
        $data['actionCategoryDesign'] = $this->url->link('extension/module/massive_data_management_nik/categoriesDesign', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		$data['user_token'] = $this->session->data['user_token'];

		$data['link_categories'] = $this->url->link('extension/module/massive_data_management_nik', 'user_token=' . $this->session->data['user_token'] . '&type=categories', true);
		$data['link_products'] = $this->url->link('extension/module/massive_data_management_nik', 'user_token=' . $this->session->data['user_token'] . '&type=products', true);

		$data['type'] = $type;

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        $this->load->model('catalog/category');

        $data['categories'] = array();

        $categories = $this->model_catalog_category->getCategories();

        foreach ($categories as $category) {
            if ($category) {
                $data['categories'][] = array(
                    'category_id' => $category['category_id'],
                    'name'       => $category['name']
                );
            }
        }

        $this->load->model('setting/store');

        $data['stores'] = array();

        $data['stores'][] = array(
            'store_id' => 0,
            'name'     => $this->language->get('text_default')
        );

        $stores = $this->model_setting_store->getStores();

        foreach ($stores as $store) {
            $data['stores'][] = array(
                'store_id' => $store['store_id'],
                'name'     => $store['name']
            );
        }

        $this->load->model('catalog/recurring');

        $data['recurrings'] = $this->model_catalog_recurring->getRecurrings();

        $this->load->model('localisation/tax_class');

        $data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

        $this->load->model('localisation/stock_status');

        $data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();

        $this->load->model('localisation/weight_class');

        $data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

        $this->load->model('localisation/length_class');

        $data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();

        $data['product_categories'] = array();

        foreach ($categories as $category_id) {
            $category_info = $this->model_catalog_category->getCategory($category_id);

            if ($category_info) {
                $data['product_categories'][] = array(
                    'category_id' => $category_info['category_id'],
                    'name'        => ($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name']
                );
            }
        }

        $this->load->model('customer/customer_group');

        $data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

        $this->load->model('tool/image');

        $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        $this->load->model('design/layout');

        $data['layouts'] = $this->model_design_layout->getLayouts();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/massive_data_management_nik', $data));
	}

    public function getProductsByCategory() {
        $json = array();

        if (isset($this->request->get['category_id'])) {
            $this->load->model('catalog/product');
            if ($this->request->get['category_id']) {
                // get by id
                $results = $this->model_catalog_product->getProductsByCategoryId($this->request->get['category_id']);
            } else {
                // get all
                $results = $this->model_catalog_product->getProducts();
            }

            foreach ($results as $result) {
                $json[] = array(
                    'product_id' => $result['product_id'],
                    'name'       => $result['name'],
                    'category_id'=> isset($result['category_id']) ? $result['category_id'] : ''
                );
            }
        }

        $sort_order = array();

        foreach ($json as $key => $value) {
            $sort_order[$key] = $value['name'];
        }

        array_multisort($sort_order, SORT_ASC, $json);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getCategoryByCategory() {
        $json = array();
        $results = array();

        if (isset($this->request->get['category_id'])) {
            $this->load->model('catalog/category');
            $this->load->model('extension/module/massive_data_management_nik');
            if ($this->request->get['category_id']) {
                // get by id
                $results[] = $this->model_catalog_category->getCategory($this->request->get['category_id']);
            } else {
                // get all
                $results = $this->model_extension_module_massive_data_management_nik->getCategories();
            }

            foreach ($results as $result) {
                $json[] = array(
                    'category_id'=> $result['category_id'],
                    'name'       => $result['name'],
                );
            }
        }

        $sort_order = array();

        foreach ($json as $key => $value) {
            $sort_order[$key] = $value['name'];
        }

        array_multisort($sort_order, SORT_ASC, $json);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function productsData() {
        $this->load->model('extension/module/massive_data_management_nik');

        if (isset($this->request->get['type'])) {
            $type = $this->request->get['type'];
        } else {
            $type = 'products';
        }

        $url = '';

        if (isset($this->request->get['type'])) {
            $url .= '&type=' . $type;
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            // process product data
            $post = $this->request->post;
            $products_id = isset($post['product']) ? $post['product'] : array();

            $products = array();

            foreach ($products_id as $product_id) {
                if (isset($post['price']) && !empty($post['price'])) {
                    $currentPrice = $this->model_extension_module_massive_data_management_nik->getProductPrice($product_id);

                    if ($post['price_sign'] == '=') {
                        $newPrice = $post['price'];
                    } elseif ($post['price_sign'] == '+') {
                        if ($post['price_ratio'] == 'number') {
                            $newPrice = (int)$currentPrice + (int)$post['price'];
                        } else {
                            $percent = 100 / (int)$post['price'];
                            $part_price = (int)$currentPrice / (int)$percent;
                            $newPrice = (int)$currentPrice + (int)$part_price;
                        }
                    } elseif ($post['price_sign'] == '-') {
                        if ($post['price_ratio'] == 'number') {
                            $newPrice = (int)$currentPrice - (int)$post['price'];
                        } else {
                            $percent = 100 / (int)$post['price'];
                            $part_price = (int)$currentPrice / (int)$percent;
                            $newPrice = (int)$currentPrice - (int)$part_price;
                        }
                    }
                }
                $products[] = array(
                    'product_id' => $product_id,
                    'price'      => isset($newPrice) ? $newPrice : ''
                );
            }

//            echo "<pre>";
//            print_r($post);
//            echo "</pre>";

//            unset($post['products_categories'], $post['category'], $post['product_name'], $post['product']);

            $emptyCounter = 0;
            foreach ($post as $item) {
                if (!empty($item) && $item != '-1') {
                    $emptyCounter++;
                }
            }

            if ($emptyCounter) {
                foreach ($products as $product) {
                    $this->model_extension_module_massive_data_management_nik->editProductData($product['product_id'], $post, $product['price']);
                }
            }

            $this->response->redirect($this->url->link('extension/module/massive_data_management_nik', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }
    }

    public function productsLinks() {
        $this->load->model('extension/module/massive_data_management_nik');

        if (isset($this->request->get['type'])) {
            $type = $this->request->get['type'];
        } else {
            $type = 'products';
        }

        $url = '';

        if (isset($this->request->get['type'])) {
            $url .= '&type=' . $type;
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            // process product links
            $post = $this->request->post;

            $products = isset($post['product']) ? $post['product'] : array();

//            echo "<pre>";
//            print_r($post);
//            echo "</pre>";

            $emptyCounter = 0;
            foreach ($post as $item) {
                if (!empty($item) && $item != '-1') {
                    $emptyCounter++;
                }
            }

            if ($emptyCounter) {
                foreach ($products as $product_id) {
                    $this->model_extension_module_massive_data_management_nik->editProductLinks($product_id, $post);
                }
            }

            $this->response->redirect($this->url->link('extension/module/massive_data_management_nik', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }
    }

    public function productsAttribute() {
        $this->load->model('extension/module/massive_data_management_nik');

        if (isset($this->request->get['type'])) {
            $type = $this->request->get['type'];
        } else {
            $type = 'products';
        }

        $url = '';

        if (isset($this->request->get['type'])) {
            $url .= '&type=' . $type;
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            // process product links
            $post = $this->request->post;

            $products = isset($post['product']) ? $post['product'] : array();

//            echo "<pre>";
//            print_r($post);
//            echo "</pre>";

            $emptyCounter = 0;
            foreach ($post as $item) {
                if (!empty($item) && $item != '-1') {
                    $emptyCounter++;
                }
            }

            if ($emptyCounter) {
                foreach ($products as $product_id) {
                    $this->model_extension_module_massive_data_management_nik->editProductAttribute($product_id, $post);
                }
            }

            $this->response->redirect($this->url->link('extension/module/massive_data_management_nik', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }
    }

    public function productsOption() {
        $this->load->model('extension/module/massive_data_management_nik');

        if (isset($this->request->get['type'])) {
            $type = $this->request->get['type'];
        } else {
            $type = 'products';
        }

        $url = '';

        if (isset($this->request->get['type'])) {
            $url .= '&type=' . $type;
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            // process product links
            $post = $this->request->post;

            $products = isset($post['product']) ? $post['product'] : array();

//            echo "<pre>";
//            print_r($post);
//            echo "</pre>";

            $emptyCounter = 0;
            foreach ($post as $item) {
                if (!empty($item) && $item != '-1') {
                    $emptyCounter++;
                }
            }

            if ($emptyCounter) {
                foreach ($products as $product_id) {
                    $this->model_extension_module_massive_data_management_nik->editProductOption($product_id, $post);
                }
            }

            $this->response->redirect($this->url->link('extension/module/massive_data_management_nik', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }
    }

    public function productsDiscount() {
        $this->load->model('extension/module/massive_data_management_nik');

        if (isset($this->request->get['type'])) {
            $type = $this->request->get['type'];
        } else {
            $type = 'products';
        }

        $url = '';

        if (isset($this->request->get['type'])) {
            $url .= '&type=' . $type;
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            // process product links
            $post = $this->request->post;

            $products = isset($post['product']) ? $post['product'] : array();

//            echo "<pre>";
//            print_r($post);
//            echo "</pre>";

            $emptyCounter = 0;
            foreach ($post as $item) {
                if (!empty($item) && $item != '-1') {
                    $emptyCounter++;
                }
            }

            if ($emptyCounter) {
                foreach ($products as $product_id) {
                    $this->model_extension_module_massive_data_management_nik->editProductDiscount($product_id, $post);
                }
            }

            $this->response->redirect($this->url->link('extension/module/massive_data_management_nik', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }
    }

    public function productsSpecial() {
        $this->load->model('extension/module/massive_data_management_nik');

        if (isset($this->request->get['type'])) {
            $type = $this->request->get['type'];
        } else {
            $type = 'products';
        }

        $url = '';

        if (isset($this->request->get['type'])) {
            $url .= '&type=' . $type;
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            // process product links
            $post = $this->request->post;

            $products = isset($post['product']) ? $post['product'] : array();

//            echo "<pre>";
//            print_r($post);
//            echo "</pre>";

            $emptyCounter = 0;
            foreach ($post as $item) {
                if (!empty($item) && $item != '-1') {
                    $emptyCounter++;
                }
            }

            if ($emptyCounter) {
                foreach ($products as $product_id) {
                    $this->model_extension_module_massive_data_management_nik->editProductSpecial($product_id, $post);
                }
            }

            $this->response->redirect($this->url->link('extension/module/massive_data_management_nik', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }
    }

    public function productsSeo() {
        $this->load->model('extension/module/massive_data_management_nik');

        if (isset($this->request->get['type'])) {
            $type = $this->request->get['type'];
        } else {
            $type = 'products';
        }

        $url = '';

        if (isset($this->request->get['type'])) {
            $url .= '&type=' . $type;
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            // process product links
            $post = $this->request->post;

            $products = isset($post['product']) ? $post['product'] : array();

//            echo "<pre>";
//            print_r($post);
//            echo "</pre>";

            if ($post['create-seo-url']) { // to unfilled
                foreach ($products as $product_id) {
                    $this->model_extension_module_massive_data_management_nik->editProductUnfilledSeo($product_id);
                }
            } else { // to all
                foreach ($products as $product_id) {
                    $this->model_extension_module_massive_data_management_nik->editProductSeo($product_id);
                }
            }

            $this->response->redirect($this->url->link('extension/module/massive_data_management_nik', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }
    }

    public function productsDesign() {
        $this->load->model('extension/module/massive_data_management_nik');

        if (isset($this->request->get['type'])) {
            $type = $this->request->get['type'];
        } else {
            $type = 'products';
        }

        $url = '';

        if (isset($this->request->get['type'])) {
            $url .= '&type=' . $type;
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            // process product links
            $post = $this->request->post;

            $products = isset($post['product']) ? $post['product'] : array();

//            echo "<pre>";
//            print_r($post);
//            echo "</pre>";

            $emptyCounter = 0;
            foreach ($post as $item) {
                if (!empty($item) && $item != '-1') {
                    $emptyCounter++;
                }
            }

            if ($emptyCounter) {
                foreach ($products as $product_id) {
                    $this->model_extension_module_massive_data_management_nik->editProductDesign($product_id, $post);
                }
            }

            $this->response->redirect($this->url->link('extension/module/massive_data_management_nik', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }
    }

    public function categoriesData() {
        $this->load->model('extension/module/massive_data_management_nik');

        if (isset($this->request->get['type'])) {
            $type = $this->request->get['type'];
        } else {
            $type = 'products';
        }

        $url = '';

        if (isset($this->request->get['type'])) {
            $url .= '&type=' . $type;
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $post = $this->request->post;

            $categories = isset($post['category']) ? $post['category'] : array();

//            echo "<pre>";
//            print_r($post);
//            echo "</pre>";

            $emptyCounter = 0;
            foreach ($post as $item) {
                if (!empty($item) && $item != '-1') {
                    $emptyCounter++;
                }
            }

            if ($emptyCounter) {
                foreach ($categories as $category_id) {
                    $this->model_extension_module_massive_data_management_nik->editCategoryData($category_id, $post);
                }
            }

            $this->response->redirect($this->url->link('extension/module/massive_data_management_nik', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }
    }

    public function categoriesSeo() {
        $this->load->model('extension/module/massive_data_management_nik');

        if (isset($this->request->get['type'])) {
            $type = $this->request->get['type'];
        } else {
            $type = 'products';
        }

        $url = '';

        if (isset($this->request->get['type'])) {
            $url .= '&type=' . $type;
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            // process product links
            $post = $this->request->post;

            $categories = isset($post['category']) ? $post['category'] : array();

//            echo "<pre>";
//            print_r($post);
//            echo "</pre>";

            if ($post['create-seo-url']) { // to unfilled
                foreach ($categories as $category_id) {
                    $this->model_extension_module_massive_data_management_nik->editCategoryUnfilledSeo($category_id);
                }
            } else { // to all
                foreach ($categories as $category_id) {
                    $this->model_extension_module_massive_data_management_nik->editCategorySeo($category_id);
                }
            }

            $this->response->redirect($this->url->link('extension/module/massive_data_management_nik', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }
    }

    public function categoriesDesign() {
        $this->load->model('extension/module/massive_data_management_nik');

        if (isset($this->request->get['type'])) {
            $type = $this->request->get['type'];
        } else {
            $type = 'products';
        }

        $url = '';

        if (isset($this->request->get['type'])) {
            $url .= '&type=' . $type;
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $post = $this->request->post;

            $categories = isset($post['category']) ? $post['category'] : array();

//            echo "<pre>";
//            print_r($post);
//            echo "</pre>";

            $emptyCounter = 0;
            foreach ($post as $item) {
                if (!empty($item) && $item != '-1') {
                    $emptyCounter++;
                }
            }

            if ($emptyCounter) {
                foreach ($categories as $category_id) {
                    $this->model_extension_module_massive_data_management_nik->editCategoryDesign($category_id, $post);
                }
            }

            $this->response->redirect($this->url->link('extension/module/massive_data_management_nik', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }
    }

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/massive_data_management_nik')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}