.. _quickstart/writingWidgets/dojoDeclare:

Declaring a widget programatically
==================================

Now that we've figured out how to change the template for the AccordionPane, how do we declare a new widget that uses that new template?

Every widget class is a Dojo class. So you should be able to extend a widget class just like you would any other Dojo class, right? Like with dojo.declare? Absolutely!

There are two ways to declare a widget, either using dojo.declare() or dijit.Declaration.
What do you gain by using dojo.declare instead of dijit.Declaration?  Mostly you gain flexibility. For example, say you wanted to declare a widget class with the same functionality, but two completely different versions of a template based on a user preferences. No problem. You construct the template itself with JavaScript code, then pass it to dojo.declare in the template property.

But let's get back to ImageAccordion. We'll place this class dojoc.layout.ImageAccordion in a file named dojoc/layout/ImageAccordion.js under the Dojo root. Here's how to write it in code:

.. code-block :: javascript

  // all packages need to dojo.provide() _something_, and only one thing
  dojo.provide("dojoc.widget.ImageAccordion");
  
  // AccordionContainer is the module with dijit.layout.AccordionPane
  dojo.require("dijit.layout.AccordionContainer"); 
  
  // our declared class
  dojo.declare("dojoc.widget.ImageAccordion", 
        // we inherit from this class, which in turn mixes 
        // in _Templated and _Layout 
        [ dijit.layout.AccordionPane ], 
        // class properties: 
        {
          templatePath: dojo.moduleUrl("dojoc","layout/templates/ImageAccordion.html"),
   
         // Necessary to keep Dijit from using templateString in AccordionPane
          templateString: "",
  
          // src: String
          //      src url for AccordionPaneExtension header
          src: ""
  });

The src string does pretty much what we think - declares src as an initialization parameter to this widget.

The templatePath requires some explanation...


Specifying the template
-----------------------
In dojo.declare land, there are two ways to specify a template:

  - Use the templatePath attribute to point to a URL with a template in it.
  - -OR- Specify the template directly in the templateString attribute

The first option is preferred because it separates the JavaScript and HTML code cleanly. With templateString, you must remember to escape all the quote marks, required in a JavaScript string. The Custom build system will convert the template in templateUrl to an inline templateString to help performance, so no need to worry there. Note: as in our above example, if you are overriding a widget with a templateString, and you want to use a templatePath in your subclass, be sure to set templateString to "".

The templates are stored in the module along with all the JavaScript code. We place this template file into dojoc/widgets/template/ImageAccordion.html:

.. code-block :: html

  <div class='dojocImageAccordion'
        ><div dojoAttachPoint='titleNode,focusNode' 
          dojoAttachEvent='ondijitclick:_onTitleClick,onkeypress:_onTitleKeyPress,onfocus:_handleFocus,onblur:_handleFocus'
                  class='dojocImageAccordionTitle' wairole="tab"
                ><img alt="${title}" src="${src}"
        ></div
        ><div><div dojoAttachPoint='containerNode' 
                style='overflow: hidden; height: 1px; display: none'
                class='dojocImageAccordionBody' waiRole="tabpanel"
        ></div></div>
  </div>
