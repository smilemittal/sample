<?php

namespace App\Traits;

// input.image
// input.text

abstract class X_TYPE{
    const TEXT = "input.text";
    const SINGLE_IMAGE = "input.image";
    const SELECT_MODELS = "models.select";
    const SEARCH_MODEL = 'search.model';
    const EMBED_TYPEFORM = 'embed.typeform';
}

trait HasDynamicModal
{

    

    public $modalComposition = [];
    
    public $showModal = false;

    public $modelPrefix = null;

    public $footer = [];

   

   

    public function initModalWithTitle(string $title, string $modelPrefix = null)
    {
        $this->modelPrefix = $modelPrefix;
        $this->modalComposition = ['title' => $title];
        $this->modalComposition['content'] = [];
        $this->modalComposition['footer'] = [];
        
    }

    
    public function injectGroupWith(Array $components){

        $componentsArray = [];

        foreach ($components as $key => $component){
            $componentsArray[] = $component;
            // $this->modalComposition['groups'][$key][] = $component;
        }

        $this->modalComposition['content'][] = $componentsArray;
    }

    public function buildComponent(string $wireModel, string $placeholder, string $xtype = "input.text", string $label = null, $options = null,$develop_submission_id= null, $checked_module_process=null){

        if (!$label){
            $label = $placeholder;
        }

        if ($xtype != X_TYPE::SINGLE_IMAGE && $wireModel != 'null'){
            $wireModel = "$this->modelPrefix$wireModel";
        }

        $component = [
            'xtype' => $xtype,
            'wireModel' => $wireModel,
            'placeholder' => $placeholder,
            'label' => $label,
            'options' => $options,
            'develop_submission_id' => $develop_submission_id,
            'checked_module_process' => $checked_module_process
        ];
        
        return $component;
    }

    public function injectComponent(string $wireModel, string $placeholder, string $xtype = "input.text", string $label = null, $options = null,$develop_submission_id = null,$checked_module_process=null){
        
        $comp = $this->buildComponent($wireModel, $placeholder, $xtype, $label, $options,$develop_submission_id);
        
        $this->modalComposition['content'][] = $comp;
        
    }

    public function injectSearchModelComponent(string $modelBasename, string $addString, string $xtype, string $removeString){

        $comp = $this->buildComponent($modelBasename, $addString, $xtype, $removeString);

        $this->modalComposition['content'][] = $comp;

    }

    public function injectFooterWithButtons(Array $buttons){
        $footerArray = [];

        foreach ($buttons as $btn){
            $footerArray[] = $btn;
        }

        $this->modalComposition['footer'] = $footerArray;
    }

    public function buildFooterButton(string $cssClass ,string $wireAction, string $title){

        $button = [
            'cssClass' => $cssClass,
            'wireAction' => $wireAction,
            'title' => $title
        ];

        return $button;
    }

    public function buildCompositionFromRules(){

    }

    public function setupDeleteDialog(string $title, string $itemDescription){
        $this->initModalWithTitle($title);

        $this->injectComponent('null', 'Confirmation needed', 'h3');

        $this->injectComponent('null', "Deleted $itemDescription will not be recoverable", 'span');

        $this->injectFooterWithButtons([
            $this->buildFooterButton("dangerbutton", "dismissModal", "No! Cancel!!" ),
            $this->buildFooterButton("goodbutton", "deletionConfirmed", "Yes! Delete!!")
        ]);
    }


    public function illegalOperationDialog(string $title, string $message){
        $this->initModalWithTitle($title);
        $this->injectComponent('null',$message,'span');
        $this->injectFooterWithButtons([
            $this->buildFooterButton("goodbutton", "dismissModal", "Understand!")
        ]);
    }
}
