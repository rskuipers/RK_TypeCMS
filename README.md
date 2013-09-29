TypeCMS
=======

Magento module which adds page types to the CMS.

![backend](http://i.imgur.com/OC4X7Ip.png)
![backend](http://i.imgur.com/qGhHK0C.png)

The above was achieved purely with xml.

Features
---
TypeCMS was developed to allow for more flexibility with the Magento CMS. TypeCMS introduces page types which have their own custom backend fields and frontend templates.
Define custom page types through XML
Define custom backend fields for your page types through XML
Define custom templates for your page types through XML
Optionally add your own backend model to handle your fields as you wish.

Usage
---
The recommended way of creating page types is by creating a separate custom module with the page types defined in its config.xml. Optionally you can just put your page types in the app/etc/modules/RK_TypeCMS.xml file.
Defining a page type
```xml
    <global>
        <page>
            <types>
                <default> <!-- This is the code of your page type (required) -->
                    <label>Default</label> <!-- The page type's label (required) -->
                    <model>typecms/page_type_default</model> <!-- Render the backend fields yourself (optional) -->
                    <template>typecms/default.phtml</template> <!-- Use this template to render the page (optional) -->
                    <attributes> <!-- A list of all the attributes for the page type (optional) -->
                        <name> <!-- Code of the attribute (required) -->
                            <label>Name</label> <!-- Label of the attribute (required) -->
                            <type>text</type> <!-- The type of data, this also determines the field to be rendered. (required) -->
                            <!-- Options: text, editor, int, select, yesno, file, image. -->
                        </name>
                        <age>
                            <label>Age</label>
                            <type>int</type>
                        </age>
                        <gender>
                            <label>Gender</label>
                            <type>select</type>
                            <options> <!-- Options of the select -->
                                <male>Male</male> <!-- "Male" is the option label, "male" is the option value. -->
                                <female>Female</female>
                            </options>
                        </gender>
                        <married>
                            <label>Married</label>
                            <type>yesno</type>
                        </married>
                        <picture>
                            <label>Picture</label>
                            <type>image</type>
                        </picture>
                        <cv>
                            <label>CV</label>
                            <type>file</type>
                        </cv>
                        <introduction>
                            <label>Introduction</label>
                            <type>editor</type>
                        </introduction>
                        <signature>
                            <label>Signature</label>
                            <type>textarea</type>
                        </signature>
                    </attributes>
                </default>
            </types>
        </page>
    </global>
```

The above defines a page type (in this case the default page type) called Default with its own template and attributes.

Changelog
---
Version 2.0.0
+ Added file field
+ Added image field
+ Added yes/no field
+ Added select field
- Changed field types to represent frontend type instead of backend type
- [Fixes #2](https://github.com/RickKuipers/TypeCMS/issues/2)
- [Fixes #1](https://github.com/RickKuipers/TypeCMS/issues/1)

Version 1.0.2 (15-07-2013)
- Fixed the int and varchar table's value column types.

Version 1.0.1 (15-07-2013)
- Fixed incorrect directory structure

Version 1.0.0 (01-08-2013)
- Initial release
