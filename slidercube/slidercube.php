<?php
if (!defined('_PS_VERSION_')) {
	exit;
}

class SliderCube extends Module 
{
	public function __construct()
	{
		$this->name = 'slidercube';
		$this->tab = 'others';
		$this->version = '1.0.0';
		$this->author = 'Sonia Arenas';
		$this->need_instance = 0;
		$this->ps_versions_compliancy = [
			'min' => '1.6',
			'max' => _PS_VERSION_
		];
		$this->bootstrap = true;

		parent::__construct();

		$this->displayName = $this->l('Modulo Slider Cube');
		$this->description = $this->l('Creación de un slider 3D');

		$this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
	}

	public function install(){
		return parent::install() &&
		$this->registerHook('displayHome') &&
		$this->registerHook('displayHeader');
		
	}

	
		public function uninstall()
{
    if (!parent::uninstall() ||
        !Configuration::deleteByName('SLIDERCUBE_MODULE')
    ) {
        return false;
    }

    return true;
}
		


		public function getContent()
		{
			$output = null;

			if (Tools::isSubmit('submit'.$this->name)) {
				$sliderCubeModule = strval(Tools::getValue('SLIDERCUBE_MODULE'));

				if (
					!$sliderCubeModule ||
					empty($sliderCubeModule) ||
					!Validate::isGenericName($sliderCubeModule)
				) {
					$output .= $this->displayError($this->l('Invalid Configuration value'));
				} else {
					$this->postProcess();
					Configuration::updateValue('SLIDERCUBE_MODULE', $sliderCubeModule);
					$output .= $this->displayConfirmation($this->l('Settings updated'));
				}
			}

			return $output.$this->displayForm();
		}

		public function displayForm()
		{
    // Get default language
			$defaultLang = (int)Configuration::get('PS_LANG_DEFAULT');

    // Init Fields form array
			$fieldsForm[0]['form'] = [
				'legend' => [
					'title' => $this->l('Settings'),
				],
				'input' => [
					[
						'type' => 'file',
						'label' => $this->l('Imagen Producto'),
						'name' => 'SLIDERCUBE_MODULE_IMG1',
						'display_image' => true,
						'required' => true
					],

					[
						'type' => 'file',
						'label' => $this->l('Imagen Producto'),
						'name' => 'SLIDERCUBE_MODULE_IMG2',
						'display_image' => true,
						'required' => true
					],

					[
						'type' => 'file',
						'label' => $this->l('Imagen Producto'),
						'name' => 'SLIDERCUBE_MODULE_IMG3',
						'display_image' => true,
						'required' => true
					],

					[
						'type' => 'file',
						'label' => $this->l('Imagen Producto'),
						'name' => 'SLIDERCUBE_MODULE_IMG4',
						'display_image' => true,
						'required' => true
					],

					[
						'type' => 'file',
						'label' => $this->l('Imagen Producto'),
						'name' => 'SLIDERCUBE_MODULE_IMG5',
						'display_image' => true,
						'required' => true
					],

					[
						'type' => 'file',
						'label' => $this->l('Imagen Producto'),
						'name' => 'SLIDERCUBE_MODULE_IMG6',
						'display_image' => true,
						'required' => true
					]
				],
				'submit' => [
					'title' => $this->l('Save'),
					'class' => 'btn btn-default pull-right',
					'name' => 'submit_form'
				]
			];

			$helper = new HelperForm();

    // Module, token and currentIndex
			$helper->module = $this;
			$helper->name_controller = $this->name;
			$helper->token = Tools::getAdminTokenLite('AdminModules');
			$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

    // Language
			$helper->default_form_language = $defaultLang;
			$helper->allow_employee_form_lang = $defaultLang;

    // Title and toolbar
			$helper->title = $this->displayName;
    $helper->show_toolbar = true;        // false -> remove toolbar
    $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
    $helper->submit_action = 'submit'.$this->name;
    $helper->toolbar_btn = [
    	'save' => [
    		'desc' => $this->l('Save'),
    		'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
    		'&token='.Tools::getAdminTokenLite('AdminModules'),
    	],
    	'back' => [
    		'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
    		'desc' => $this->l('Back to list')
    	]
    ];

    // Load current value
    $helper->fields_value['SLIDERCUBE_MODULE'] = Tools::getValue('SLIDERCUBE_MODULE', Configuration::get('SLIDERCUBE_MODULE'));

    return $helper->generateForm($fieldsForm);
}
public function postProcess(){
	if (Tools::isSubmit('submit_form'))
		{	
			//file upload code
			if (isset($_FILES['file_url'])) 
			{	
				$target_dir = _PS_UPLOAD_DIR_;
				$target_file = $target_dir . basename($_FILES['file_url']["name"]);	
				$uploadOk = 1;
				$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
				// Check if image file is a actual image or fake image
				if(isset($_POST["submit"])) 
				{
					$check = getimagesize($_FILES['file_url']["tmp_name"]);
					if($check !== false) {
						echo "File is an image - " . $check["mime"] . ".";
						$uploadOk = 1;
					} else {
						echo "File is not an image.";
						$uploadOk = 0;
					}
				}
				// Check if file already exists
				if (file_exists($target_file)) {
					echo "Sorry, file already exists.";
					$uploadOk = 0;
				}
				// Allow certain file formats
				if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
				&& $imageFileType != "gif" ) {
					echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
					$uploadOk = 0;
				}
				// Check if $uploadOk is set to 0 by an error
				if ($uploadOk == 0) {
					echo "Sorry, your file was not uploaded.";
				}
				else 
				{
					if (move_uploaded_file($_FILES['file_url']["tmp_name"], $target_file)) 
					{
						echo "The file ". basename($_FILES['file_url']["name"]). " has been uploaded.";
						$file_location = basename($_FILES['file_url']["name"]);
					} 
					else 
					{
						echo "Sorry, there was an error uploading your file.";
					}
				}
				
			}

}
}
 public function hookDisplayHome($params)
   {



        return $this->context->smarty->fetch($this->local_path.'views/templates/hook/slider.tpl');
    }
    
    public function hookDisplayHeader($params)
    {
        if($this->context->controller->php_self && $this->context->controller->php_self == 'index')
        {
         $this->context->controller->addCSS($this->local_path.'views/css/style.css');
          $this->context->controller->addJS($this->local_path.'views/js/script.js');
        }
       
    }


}


?>