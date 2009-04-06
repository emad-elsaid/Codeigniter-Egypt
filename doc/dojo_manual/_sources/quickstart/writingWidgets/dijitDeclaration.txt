.. _quickstart/writingWidgets/dijitDeclaration:

TODO: REMOVE THIS PAGE, IT'S BEEN MOVED TO :ref:`dijit.Declaration <dijit/Declaration>`.







Just as there are two ways to create a widget instances - declarative and programmatic - so there are two ways to declare a Dijit class. As you might guess, the declarative way is slightly easier.

To declare your class declaratively, use dijit.Declaration. Uhhhh, OK, too many "declare"s in that sentence. It's easier to show than to tell. Here's the entire ImageAccordion spec. The code doesn't display anything itself, so it's best to place it right after the BODY tag before any other displayable code, or at the end.

.. code-block :: html

  <div dojoType="dijit.Declaration" 
       widgetClass="dojoc.widget.ImageAccordion" 
       mixins="dijit.layout.AccordionPane"><div class='dijitAccordionPane'
      ><div dojoAttachPoint='titleNode,focusNode' 
                dojoAttachEvent='ondijitclick:_onTitleClick,onkeypress:_onKeyPress'
                class='dojocAccordionTitle' wairole="tab"
                ><div class='dijitAccordionArrow'></div
                ><div class='arrowTextUp' waiRole="presentation">▲</div
                ><div class='arrowTextDown' waiRole="presentation">▼</div
                ><span dojoAttachPoint='titleTextNode'><img alt="${title}" src="${src}"
        ></span></div
        ><div><div dojoAttachPoint='containerNode' 
                style='overflow: hidden; height: 1px; display: none'
                dojoAttachEvent='onkeypress:_onKeyPress'
                class='dojocImageAccordionBody' waiRole="tabpanel"
        ></div></div>
    </div>
  </div>

Dijit.Declaration turns this markup into a templated widget class. The mixins attribute tells which classes ImageAccordion will be based on. You can include more than one class here - each separated by commas. The first class is not technically a mixin - it's the parent class of this new widget. So all of the methods in AccordionPane are inherited in ImageAccordion. Only the template changes.

Declaring extension point implementations, or connecting to events inside a dijit.Declaration is exactly the same as for a declaratively-defined individual widget. So:

.. code-block :: html

  <div dojoType="dijit.Declaration" widgetClass="simpleConnectedWidget" >
     Just a plain ol' piece of text
     <script type="dojo/connect" event="dblclick">
        console.debug("Ouch!  I've been double-clicked");
     </script>
  </div>

Every widget declared with class simpleConnectedWidget will have the handler connected to it.

Notes
-----
- widgetsInTemplate is automatically set to true, so any widgets you place in the template will be automatically filled in.
- If you do not specify mixin, the widget class will be a subclass of dijit._Widget and mix in dijit._Templated. If you specify mixin, the first class listed must be a subclass of dijit._Widget. At least one of the mixins should itself mixin dijit._Templated, or you should supply dijit._Templated yourself as a mixin.
- Only one extension point implementation of preamble.
