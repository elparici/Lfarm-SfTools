<?php
namespace Lfarm\DevCollabBundle\Services;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\FormBuilder;

use Lfarm\DevCollabBundle\Entity\Ticket;
use Lfarm\DevCollabBundle\Form\TicketType;

class TwigFunctionsService
{
    public function generateIndex($entity, $bundle, $fieldNames, $col, $prefix)
    {
        $data = array();
        $data[] = $this->basicExtends($bundle->getName());
        $data[] = $this->startBlock();
        $data[] = $this->getNewEntityLink(strtolower($entity), $prefix);
        $data[] = $this->startLoop(strtolower($entity));
        $data[] = $this->getBootstrapBlock($entity, $fieldNames, $col);
        $data[] = $this->endLoop();
        $data[] = $this->endBlock();
        return $data;
    }

    public function getBootstrapBlock($entity, $fieldNames, $col)
    {
            
        $Block = "\n\t<div class='col-xs-$col'>" ;
        foreach ($fieldNames as $fieldName) {
            $Block = $Block . "\n\t\t{{ ($entity.$fieldName) }}";
        }
        $Block = $Block . "\n\t</div>\n";
        return $Block;
    }

    public function getNewEntityLink($entity, $prefix)
    {
        var_dump($prefix);
        return "\t\t\n<div class='col-xs-12'>\n\t<a class='btn btn-default pull-right' style='margin-right:20px;' href='{{ path('{$prefix}_{$entity}_new') }}'><i class='fa fa-plus fa-2x'></i>&nbsp;New {$entity}</a>\n</div>";
    }

    public function getBreadCrumb($entity)
    {
        $header = "
    <h1>
        {% if delete_form is defined %}
            {% set levels = ['" . $entity . "', 'Edition'] %}
            {% set icon = 'fa-pencil' %}
        {% else %}
            {% set levels = ['" . $entity . "', 'Création'] %}
            {% set icon = 'fa-plus' %}
        {% endif %}

        {% include 'Commons/Breadcrumb.html.twig' with {'levels' : levels, 'icon' : icon }%}
    </h1>\n" ;
        return $header;
    }

    public function getForm($field){
   
    return "\n<div class='col-xs-12'>{{ form_widget(form.$field) }}</div>";

    }

    public function startLoop($entity)
    {
        $entities = $entity . "s";

        return "\n\t{% for $entity in $entities %}\n";
    }

    public function endLoop()
    {
        return "\n\t{% endfor %}\n";
    }

    public function startBlock()
    {
        return "\n{% block body %}\n" ;
    }

    public function endBlock()
    {
        return "\n{% endblock %}\n";
    }

        public function startJsBlock()
    {
        return "\n{% block jsContent %}\n" ;
    }

    public function startForm()
    {
        return "\n{{ form_start(form) }}";
    }

    public function endForm()
    {
        return "\n{{ form_end(form) }}";
    }

    public function createMenu($prefix, $entity)
    {
        $data = "
        <ul class='col-xs-12'>
            <li class='col-xs-4'>
                <a href='{{ path('{$prefix}_{$entity}_index') }}'>Back to the list</a>
            </li>
            <li class='col-xs-4'>
             {% if delete_form is defined %}{% set value = 'Edit' %}{% else %}{% set value = 'Create' %}{% endif %}
                <input type='submit' value='{{value}}' />
                {{ form_end(form) }}
            </li>
            {% if delete_form is defined %}
                <li class='col-xs-4'>
                    {{ form_start(delete_form) }}
                        <input type='submit' value='Delete'>
                    {{ form_end(delete_form) }}
                </li>
            {% endif %}
        </ul>
        " ;
        return $data;
    }

    public function basicExtends($bundle)
    {
        return "\n{% extends '$bundle::layout.html.twig' %}\n";
    }

    public function basicUse($bundle, $entity)
    {
        return "\n{% use '$bundle:$entity:form.html.twig' %}\n";
    }

    public function getCollectionForm($bundle, $fieldName, $targetEntity)
    {
        
        $template = $bundle .":". $targetEntity .":form.html.twig";
        $data = "\n
            {% include 'Commons/collectionForm.html.twig' with { 
                'form' : form,
                'name': $fieldName,
                'picto_url' : null ,
                'formTemplate' : '$template',
                'formPrototype' : form.$fieldName.vars.prototype,
                'elements' : form.$fieldName,
                'formSuffixe': '$fieldName',
                }
            %}";
        return $data;
    }

    public function getJsBasicCollectionValues()
    {
        $NewFormLi = '<li class="col-md-12"></li>';
        $btnDelete = '<a class="col-md-12 btn btn-default sup_btn" href="#" title="Supprimer"><i class="fa fa-1x fa-trash "></i></a>';
        $AddTagLink = '<div class="col-md-12"><a href="#" class="col-md-12 btn btn-default add_link"><i class="fa fa-plus"></i></a></div>';
        $data= "
        {% set NewFormLi = '$NewFormLi' %}
        {% set btnDelete = '$btnDelete' %}
        {% set AddTagLink = '$AddTagLink'  %}
        ";
        return $data;
    }
    public function getJsCollectionHolder($fieldName)
    {
        $data= "
        {% include 'Commons/collectionHolderJs.html.twig' with {
            'suffixe': '$fieldName' ,
            'holder': 'ul.$fieldName',
            'btnDelete' : btnDelete,
            'NewFormLi' : NewFormLi,
            'AddTagLink' : AddTagLink
            }
        %}
        ";
        return $data;
    }


   
}
