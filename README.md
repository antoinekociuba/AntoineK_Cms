# AntoineK_Cms

## What for?

I'm not taking any risk by saying that CMS blocks are widely used on Magento. The problem is that, they do not offer much flexibilities with only one WYSIWYG field...

This module aims to extend default CMS blocks functionnalies by offering an easy additionnal fields configuration, and by adding the use of custom templates for rendering (inspired from [Mbiz_Cms](https://github.com/monsieurbiz/Mbiz_Cms)).

## How-To use it?

Out of the box, the module does absolutely nothing until you have set it up properly inside your project.

Let's imagine you have just installed it, and you have got the following module inside you project:

```
├── app
│   ├── code
│   │   ├── local
│   │   │   ├── YourNamespace
│   │   │   │   ├── ModuleName
│   │   │   │   │    ├── etc
│   │   │   │   │    │    ├── config.xml
│   │   │   │   │    │    ├── widget.xml
```

### How-To add custom CMS block fields?

Let's add the following inside `config.xml` file:

```xml
<?xml version="1.0" encoding="utf-8"?>
<config>
    <global>
    
        ...
        
        <!-- belows are examples of additional fiedsets/fields declarations -->
        <cms>
            <block>
                <additional_fields>
                    <!-- Fieldset declaration -->
                    <fieldset_one translate="label" module="namespace_module">
                        <label>Fieldset One</label>
                        <!-- Fields declaration -->
                        <fields>
                            <text_field translate="label" module="namespace_module">
                                <!-- You can give same parameters as you would do with classic Varien_Data_Form_Element_Fieldset::addField() method -->
                                <type>text</type>
                                <label>Text</label>
                                <title>Text</title>
                                <required>0</required>
                            </text_field>
                            <textarea_field translate="label title" module="namespace_module">
                                <type>textarea</type>
                                <label>Textarea</label>
                                <title>Textarea</title>
                                <required>1</required>
                            </textarea_field>
                            <editor translate="label title" module="namespace_module">
                                <!-- WYSIWYG editor field -->
                                <type>editor</type>
                                <label>Editor</label>
                                <title>Editor</title>
                            </editor>
                            <select translate="label title" module="namespace_module">
                                <type>select</type>
                                <label>Select</label>
                                <title>Select</title>
                                <options>
                                    <value1>Option1</value1>
                                    <value2>Option2</value2>
                                </options>
                                <!-- Note that you can grab option values by using config or helper/model method -->
                                <!--
                                <options config="foo/bar/baz">
                                <options helper="my_module/helper::getOptionsMethod">
                                <options model="my_module/model::getOptionsMethod">
                                -->
                            </select>
                            <mediachooser translate="label title" module="namespace_module">
                                <!-- @see https://github.com/antoinekociuba/AntoineK_MediaChooserField -->
                                <type>mediachooser</type>
                                <label>AntoineK Media Chooser</label>
                                <title>AntoineK Media Chooser</title>
                            </mediachooser>
                        </fields>
                    </fieldset_one>
                    <fieldset_two translate="label" module="namespace_module">
                        <label>Fieldset Two</label>
                        <fields>
                            <text_field_two translate="label" module="namespace_module">
                                <type>text</type>
                                <label>Text Field Label</label>
                                <title>Text Field Title</title>
                            </text_field_two>
                        </fields>
                    </fieldset_two>
                </additional_fields>
            </block>
        </cms>
        
        ...
        
    </global>
</config>
```

If you now go to CMS block edit page, you should see 2 new fieldsets with some new various fields.

Those extra data will be all saved together (JSON format) inside a new DB table `cms_block_additional`.

### How-To use those extra data on frontend?

You could simply define a template on any CMS block added through layout files:
```xml
<default>
    <reference name="footer">
        <block type="cms/block" name="cms_footer_links" before="footer_links" template="foo/bar/baz.phtml">
            <action method="setBlockId"><block_id>footer_links</block_id></action>
        </block>
    </reference>
</default>
```

And then, inside `foo/bar/baz.phtml` template, just use the magic of Varien_Object:

```php
<?php
/** @var AntoineK_Cms_Block_Rewrite_Block $_block */
$_block = $this->getCmsBlock();

/**
 * Text field content (Fieldset One)
 */
$_text = $_block->getFieldsetOne('text_field'); 
// Or $_text = $_block->getData('fieldset_one', 'text_field');

/**
 * Editor field filtered content (Fieldset One)
 */
$_editor = $_block->getFilteredData('fieldset_one', 'editor');

...

?>
```

You could also define extra templates for default CMS block widget, in your `widget.xml` file, in order to use same benefits with widgets:

```xml
<widgets>
    <cms_static_block>
        <parameters>
            <template>
                <values>
                    <extra_template1 translate="label" module="namespace_module">
                        <value>cms/widget/static_block/extra/template1.phtml</value>
                        <label>CMS Static Block Extra Template 1</label>
                    </extra_template1>
                    <extra_template2 translate="label" module="namespace_module">
                        <value>cms/widget/static_block/extra/template2.phtml</value>
                        <label>CMS Static Block Extra Template 2</label>
                    </extra_template2>
                </values>
            </template>
        </parameters>
    </cms_static_block>
</widgets>
```

Enjoy! ;-)

