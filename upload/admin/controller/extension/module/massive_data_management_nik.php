<?php
class ControllerExtensionModuleMassiveDataManagementNik extends Controller {
	private $error = array();

	public function index() {
        $this->load->language('catalog/product');
        $this->load->language('catalog/category');
        $this->load->language('catalog/information');
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

        if (isset($this->error['empty_products'])) {
            $data['error_empty_products'] = $this->error['empty_products'];
        } else {
            $data['error_empty_products'] = '';
        }

        if (isset($this->error['empty_categories'])) {
            $data['error_empty_categories'] = $this->error['empty_categories'];
        } else {
            $data['error_empty_categories'] = '';
        }

        if (isset($this->error['empty_information'])) {
            $data['error_empty_information'] = $this->error['empty_information'];
        } else {
            $data['error_empty_information'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->request->get['type'])) {
            $type = $this->request->get['type'];
        } else {
            $type = 'categories';
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

        $data['actionInformationData'] = $this->url->link('extension/module/massive_data_management_nik/informationData', 'user_token=' . $this->session->data['user_token'] . $url, true);
        $data['actionInformationSeo'] = $this->url->link('extension/module/massive_data_management_nik/informationSeo', 'user_token=' . $this->session->data['user_token'] . $url, true);
        $data['actionInformationDesign'] = $this->url->link('extension/module/massive_data_management_nik/informationDesign', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		$data['user_token'] = $this->session->data['user_token'];

		$data['link_categories'] = $this->url->link('extension/module/massive_data_management_nik', 'user_token=' . $this->session->data['user_token'] . '&type=categories', true);
		$data['link_products'] = $this->url->link('extension/module/massive_data_management_nik', 'user_token=' . $this->session->data['user_token'] . '&type=products', true);
		$data['link_information'] = $this->url->link('extension/module/massive_data_management_nik', 'user_token=' . $this->session->data['user_token'] . '&type=information', true);

		$data['type'] = $type;

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        $this->load->model('catalog/category');

        $data['categories'] = array();

        $categories = $this->model_catalog_category->getCategories();

        $this->load->model('extension/module/massive_data_management_nik');

        foreach ($categories as $category) {
            if ($category) {
                $total_products = $this->model_extension_module_massive_data_management_nik->getTotalProductsByCategoryId($category['category_id']);
                $data['categories'][] = array(
                    'category_id'   => $category['category_id'],
                    'name'          => $category['name'],
                    'total_products'=> sprintf($this->language->get('text_total_products'), $total_products)
                );
            }
        }

        $sort_order = array();

        foreach ($data['categories'] as $key => $value) {
            $sort_order[$key] = $value['name'];
        }

        array_multisort($sort_order, SORT_ASC, $data['categories']);

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

        $this->load->model('catalog/information');

        $informations = $this->model_extension_module_massive_data_management_nik->getAllInformationsDescription();

        foreach ($informations as $information) {
            if ($information) {
                $data['information'][] = array(
                    'information_id'   => $information['information_id'],
                    'title'         => $information['title'],
                );
            }
        }

        $sort_order = array();

        foreach ($data['information'] as $key => $value) {
            $sort_order[$key] = $value['title'];
        }

        array_multisort($sort_order, SORT_ASC, $data['information']);

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
            $this->load->model('extension/module/massive_data_management_nik');

            if ($this->request->get['category_id']) {
                // get by id
                $results = $this->model_catalog_product->getProductsByCategoryId($this->request->get['category_id']);
            } else {
                // get all
                $results = $this->model_extension_module_massive_data_management_nik->getProductsWithCategories();
            }
            foreach ($results as $result) {
                $products_categories = $this->model_catalog_product->getProductCategories($result['product_id']);
                $json[] = array(
                    'product_id'    => $result['product_id'],
                    'name'          => $result['name'],
                    'categories_id' => isset($products_categories) ? $products_categories : array(),
                    'category_id'   => $this->request->get['category_id'] ? $this->request->get['category_id'] : '',
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

    public function getAllCategories() {
        $json = array();
        $results = array();

        $this->load->model('extension/module/massive_data_management_nik');

        $results = $this->model_extension_module_massive_data_management_nik->getCategories();

        foreach ($results as $result) {
            $json[] = array(
                'category_id'=> $result['category_id'],
                'name'       => $result['name'],
            );
        }

        $sort_order = array();

        foreach ($json as $key => $value) {
            $sort_order[$key] = $value['name'];
        }

        array_multisort($sort_order, SORT_ASC, $json);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function autocompleteInformation() {
        $json = array();

        if (isset($this->request->get['filter_name'])) {
            $this->load->model('extension/module/massive_data_management_nik');

            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }

            if (isset($this->request->get['limit'])) {
                $limit = $this->request->get['limit'];
            } else {
                $limit = 5;
            }

            $filter_data = array(
                'filter_name'  => $filter_name,
                'start'        => 0,
                'limit'        => $limit
            );

            $results = $this->model_extension_module_massive_data_management_nik->getInformations($filter_data);

            foreach ($results as $result) {
                $json[] = array(
                    'information_id' => $result['information_id'],
                    'title'          => strip_tags(html_entity_decode($result['title'], ENT_QUOTES, 'UTF-8')),
                );
            }

            $sort_order = array();

            foreach ($json as $key => $value) {
                $sort_order[$key] = $value['title'];
            }

            array_multisort($sort_order, SORT_ASC, $json);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getInformationByInformation() {
        $json = array();
        $results = array();

        if (isset($this->request->get['information_id'])) {
            $this->load->model('catalog/information');
            $this->load->model('extension/module/massive_data_management_nik');
            if ($this->request->get['information_id']) {
                // get by id
                $results[] = $this->model_extension_module_massive_data_management_nik->getInformationDescription($this->request->get['information_id']);
            } else {
                // get all
                $results = $this->model_extension_module_massive_data_management_nik->getAllInformationsDescription();
            }

            foreach ($results as $result) {
                $json[] = array(
                    'information_id' => $result['information_id'],
                    'title'          => $result['title'],
                );
            }
        }

        $sort_order = array();

        foreach ($json as $key => $value) {
            $sort_order[$key] = $value['title'];
        }

        array_multisort($sort_order, SORT_ASC, $json);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getAllInformation() {
        $json = array();

        $this->load->model('extension/module/massive_data_management_nik');

        $results = $this->model_extension_module_massive_data_management_nik->getAllInformationsDescription();

        foreach ($results as $result) {
            $json[] = array(
                'information_id' => $result['information_id'],
                'title'          => strip_tags(html_entity_decode($result['title'], ENT_QUOTES, 'UTF-8')),
            );
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function productsData() {
	    $this->load->language('extension/module/massive_data_management_nik');
        $this->load->model('extension/module/massive_data_management_nik');

        if (isset($this->request->get['type'])) {
            $type = $this->request->get['type'];
        } else {
            $type = 'categories';
        }

        $url = '';

        if (isset($this->request->get['type'])) {
            $url .= '&type=' . $type;
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate() && $this->validateProducts()) {
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

            $this->session->data['success'] = $this->language->get('text_apply_success');

            $this->response->redirect($this->url->link('extension/module/massive_data_management_nik', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->index();
    }

    public function productsLinks() {
	    $this->load->language('extension/module/massive_data_management_nik');
        $this->load->model('extension/module/massive_data_management_nik');

        if (isset($this->request->get['type'])) {
            $type = $this->request->get['type'];
        } else {
            $type = 'categories';
        }

        $url = '';

        if (isset($this->request->get['type'])) {
            $url .= '&type=' . $type;
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate() && $this->validateProducts()) {
            // process product links
            $post = $this->request->post;

            $products = isset($post['product']) ? $post['product'] : array();

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

            $this->session->data['success'] = $this->language->get('text_apply_success');

            $this->response->redirect($this->url->link('extension/module/massive_data_management_nik', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->index();
    }

    public function productsAttribute() {
	    $this->load->language('extension/module/massive_data_management_nik');
        $this->load->model('extension/module/massive_data_management_nik');

        if (isset($this->request->get['type'])) {
            $type = $this->request->get['type'];
        } else {
            $type = 'categories';
        }

        $url = '';

        if (isset($this->request->get['type'])) {
            $url .= '&type=' . $type;
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate() && $this->validateProducts()) {
            // process product links
            $post = $this->request->post;

            $products = isset($post['product']) ? $post['product'] : array();

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

            $this->session->data['success'] = $this->language->get('text_apply_success');

            $this->response->redirect($this->url->link('extension/module/massive_data_management_nik', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

         $this->index();
    }

    public function productsOption() {
        $this->load->language('extension/module/massive_data_management_nik');
        $this->load->model('extension/module/massive_data_management_nik');

        if (isset($this->request->get['type'])) {
            $type = $this->request->get['type'];
        } else {
            $type = 'categories';
        }

        $url = '';

        if (isset($this->request->get['type'])) {
            $url .= '&type=' . $type;
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate() && $this->validateProducts()) {
            // process product links
            $post = $this->request->post;

            $products = isset($post['product']) ? $post['product'] : array();

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

            $this->session->data['success'] = $this->language->get('text_apply_success');

            $this->response->redirect($this->url->link('extension/module/massive_data_management_nik', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->index();
    }

    public function productsDiscount() {
	    $this->load->language('extension/module/massive_data_management_nik');
        $this->load->model('extension/module/massive_data_management_nik');

        if (isset($this->request->get['type'])) {
            $type = $this->request->get['type'];
        } else {
            $type = 'categories';
        }

        $url = '';

        if (isset($this->request->get['type'])) {
            $url .= '&type=' . $type;
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate() && $this->validateProducts()) {
            // process product links
            $post = $this->request->post;

            $products = isset($post['product']) ? $post['product'] : array();

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

            $this->session->data['success'] = $this->language->get('text_apply_success');

            $this->response->redirect($this->url->link('extension/module/massive_data_management_nik', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->index();
    }

    public function productsSpecial() {
	    $this->load->language('extension/module/massive_data_management_nik');
        $this->load->model('extension/module/massive_data_management_nik');

        if (isset($this->request->get['type'])) {
            $type = $this->request->get['type'];
        } else {
            $type = 'categories';
        }

        $url = '';

        if (isset($this->request->get['type'])) {
            $url .= '&type=' . $type;
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate() && $this->validateProducts()) {
            // process product links
            $post = $this->request->post;

            $products = isset($post['product']) ? $post['product'] : array();

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

            $this->session->data['success'] = $this->language->get('text_apply_success');

            $this->response->redirect($this->url->link('extension/module/massive_data_management_nik', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->index();
    }

    public function productsReward() {
        $this->load->language('extension/module/massive_data_management_nik');
        $this->load->model('extension/module/massive_data_management_nik');

        if (isset($this->request->get['type'])) {
            $type = $this->request->get['type'];
        } else {
            $type = 'categories';
        }

        $url = '';

        if (isset($this->request->get['type'])) {
            $url .= '&type=' . $type;
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate() && $this->validateProducts()) {
            // process product links
            $post = $this->request->post;

            $products = isset($post['product']) ? $post['product'] : array();

            $emptyCounter = 0;
            foreach ($post as $item) {
                if (!empty($item) && $item != '-1') {
                    $emptyCounter++;
                }
            }

            if ($emptyCounter) {
                foreach ($products as $product_id) {
                    $this->model_extension_module_massive_data_management_nik->editProductReward($product_id, $post);
                }
            }

            $this->session->data['success'] = $this->language->get('text_apply_success');

            $this->response->redirect($this->url->link('extension/module/massive_data_management_nik', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->index();
    }

    public function productsSeo() {
	    $this->load->language('extension/module/massive_data_management_nik');
        $this->load->model('extension/module/massive_data_management_nik');

        if (isset($this->request->get['type'])) {
            $type = $this->request->get['type'];
        } else {
            $type = 'categories';
        }

        $url = '';

        if (isset($this->request->get['type'])) {
            $url .= '&type=' . $type;
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate() && $this->validateProducts()) {
            // process product links
            $post = $this->request->post;

            $products = isset($post['product']) ? $post['product'] : array();

            if ($post['create-seo-url']) { // to unfilled
                foreach ($products as $product_id) {
                    $this->model_extension_module_massive_data_management_nik->editProductUnfilledSeo($product_id);
                }
            } else { // to all
                foreach ($products as $product_id) {
                    $this->model_extension_module_massive_data_management_nik->editProductSeo($product_id);
                }
            }

            $this->session->data['success'] = $this->language->get('text_apply_success');

            $this->response->redirect($this->url->link('extension/module/massive_data_management_nik', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->index();
    }

    public function productsDesign() {
	    $this->load->language('extension/module/massive_data_management_nik');
        $this->load->model('extension/module/massive_data_management_nik');

        if (isset($this->request->get['type'])) {
            $type = $this->request->get['type'];
        } else {
            $type = 'categories';
        }

        $url = '';

        if (isset($this->request->get['type'])) {
            $url .= '&type=' . $type;
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate() && $this->validateProducts()) {
            // process product links
            $post = $this->request->post;

            $products = isset($post['product']) ? $post['product'] : array();

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

            $this->session->data['success'] = $this->language->get('text_apply_success');

            $this->response->redirect($this->url->link('extension/module/massive_data_management_nik', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->index();
    }

    public function categoriesData() {
	    $this->load->language('extension/module/massive_data_management_nik');
        $this->load->model('extension/module/massive_data_management_nik');

        if (isset($this->request->get['type'])) {
            $type = $this->request->get['type'];
        } else {
            $type = 'categories';
        }

        $url = '';

        if (isset($this->request->get['type'])) {
            $url .= '&type=' . $type;
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate() && $this->validateCategories()) {
            $post = $this->request->post;

            $categories = isset($post['category']) ? $post['category'] : array();

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

            $this->session->data['success'] = $this->language->get('text_apply_success');

            $this->response->redirect($this->url->link('extension/module/massive_data_management_nik', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->index();
    }

    public function categoriesSeo() {
	    $this->load->language('extension/module/massive_data_management_nik');
        $this->load->model('extension/module/massive_data_management_nik');

        if (isset($this->request->get['type'])) {
            $type = $this->request->get['type'];
        } else {
            $type = 'categories';
        }

        $url = '';

        if (isset($this->request->get['type'])) {
            $url .= '&type=' . $type;
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate() && $this->validateCategories()) {
            // process product links
            $post = $this->request->post;

            $categories = isset($post['category']) ? $post['category'] : array();

            if ($post['create-seo-url']) { // to unfilled
                foreach ($categories as $category_id) {
                    $this->model_extension_module_massive_data_management_nik->editCategoryUnfilledSeo($category_id);
                }
            } else { // to all
                foreach ($categories as $category_id) {
                    $this->model_extension_module_massive_data_management_nik->editCategorySeo($category_id);
                }
            }

            $this->session->data['success'] = $this->language->get('text_apply_success');

            $this->response->redirect($this->url->link('extension/module/massive_data_management_nik', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->index();
    }

    public function categoriesDesign() {
        $this->load->language('extension/module/massive_data_management_nik');
        $this->load->model('extension/module/massive_data_management_nik');

        if (isset($this->request->get['type'])) {
            $type = $this->request->get['type'];
        } else {
            $type = 'categories';
        }

        $url = '';

        if (isset($this->request->get['type'])) {
            $url .= '&type=' . $type;
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate() && $this->validateCategories()) {
            $post = $this->request->post;

            $categories = isset($post['category']) ? $post['category'] : array();

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

            $this->session->data['success'] = $this->language->get('text_apply_success');

            $this->response->redirect($this->url->link('extension/module/massive_data_management_nik', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->index();
    }

    public function informationData() {
        $this->load->language('extension/module/massive_data_management_nik');
        $this->load->model('extension/module/massive_data_management_nik');

        if (isset($this->request->get['type'])) {
            $type = $this->request->get['type'];
        } else {
            $type = 'categories';
        }

        $url = '';

        if (isset($this->request->get['type'])) {
            $url .= '&type=' . $type;
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate() && $this->validateInformation()) {
            $post = $this->request->post;

            $information = isset($post['information']) ? $post['information'] : array();

            $emptyCounter = 0;
            foreach ($post as $item) {
                if (!empty($item) && $item != '-1') {
                    $emptyCounter++;
                }
            }

            if ($emptyCounter) {
                foreach ($information as $information_id) {
                    $this->model_extension_module_massive_data_management_nik->editInformationData($information_id, $post);
                }
            }

            $this->session->data['success'] = $this->language->get('text_apply_success');

            $this->response->redirect($this->url->link('extension/module/massive_data_management_nik', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->index();
    }

    public function informationSeo() {
        $this->load->language('extension/module/massive_data_management_nik');
        $this->load->model('extension/module/massive_data_management_nik');

        if (isset($this->request->get['type'])) {
            $type = $this->request->get['type'];
        } else {
            $type = 'categories';
        }

        $url = '';

        if (isset($this->request->get['type'])) {
            $url .= '&type=' . $type;
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate() && $this->validateInformation()) {
            // process product links
            $post = $this->request->post;

            $information = isset($post['information']) ? $post['information'] : array();

            if ($post['create-seo-url']) { // to unfilled
                foreach ($information as $information_id) {
                    $this->model_extension_module_massive_data_management_nik->editInformationUnfilledSeo($information_id);
                }
            } else { // to all
                foreach ($information as $information_id) {
                    $this->model_extension_module_massive_data_management_nik->editInformationSeo($information_id);
                }
            }

            $this->session->data['success'] = $this->language->get('text_apply_success');

            $this->response->redirect($this->url->link('extension/module/massive_data_management_nik', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->index();
    }

    public function informationDesign() {
        $this->load->language('extension/module/massive_data_management_nik');
        $this->load->model('extension/module/massive_data_management_nik');

        if (isset($this->request->get['type'])) {
            $type = $this->request->get['type'];
        } else {
            $type = 'categories';
        }

        $url = '';

        if (isset($this->request->get['type'])) {
            $url .= '&type=' . $type;
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate() && $this->validateInformation()) {
            $post = $this->request->post;

            $information = isset($post['information']) ? $post['information'] : array();

            $emptyCounter = 0;
            foreach ($post as $item) {
                if (!empty($item) && $item != '-1') {
                    $emptyCounter++;
                }
            }

            if ($emptyCounter) {
                foreach ($information as $information_id) {
                    $this->model_extension_module_massive_data_management_nik->editInformationDesign($information_id, $post);
                }
            }

            $this->session->data['success'] = $this->language->get('text_apply_success');

            $this->response->redirect($this->url->link('extension/module/massive_data_management_nik', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->index();
    }

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/massive_data_management_nik')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

    protected function validateProducts() {
        if (empty($this->request->post['product'])) {
            $this->error['empty_products'] = $this->language->get('error_empty_products');
        }

        return !$this->error;
    }

    protected function validateCategories() {
        if (empty($this->request->post['category'])) {
            $this->error['empty_categories'] = $this->language->get('error_empty_categories');
        }

        return !$this->error;
    }

    protected function validateInformation() {
        if (empty($this->request->post['information'])) {
            $this->error['empty_information'] = $this->language->get('error_empty_information');
        }

        return !$this->error;
    }
}