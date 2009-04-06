.. _quickstart/writingWidgets/lifecycle:

TODO: DELETE THIS PAGE, IT'S BEEN ROLLED INTO :ref:`dijit._Widget <dijit/_Widget>` and :ref:`dijit.layout._LayoutWidget <dijit/layout/_LayoutWidget>`.

Widget Lifecycle
================

The lifecycle of a widget decribes the phases of its creation and destruction which you can hook into. It's useful to understand exactly what happens when. Whether you are sub-classing an existing widget, using dojo/method script blocks, or passing in method overrides to the constructor, these are your entry points for making a widget do what you want it to do.

Widgets are classes, created with dojo.declare. All widgets inherit from dijit._Widget, and most get the _Templated mixin. That provides you the following extension points (methods) you can override and provide implementation for:

- constructor
     Your constructor method will be called before the parameters are mixed into the widget, and can be used to initialize arrays, etc.

- parameters are mixed into the widget instance
     This is when attributes in the markup (ex: <button iconClass=...>) are mixed in or, if you are instantiating directly, the properties object you passed into the constructor (ex: new dijit.form.Button({label: "hi"})). This step itself is not overridable, but you can play with the result in...

- postMixInProperties
     If you provide a postMixInProperties method for your widget, it will be invoked before rendering occurs, and before any dom nodes are created. If you need to add or change the instance's properties before the widget is rendered - this is the place to do it.

- buildRendering
     _Templated provides an implementation of buildRendering that most times will do what you need. The template is fetched/read, nodes created and events hooked up during buildRendering.  The end result is assigned to this.domNode.   If you don't mixin _Templated (and most OOTB dijits do) and want to handle rendering yourself (e.g. to really streamline a simple widget, or even use a different templating system) this is where you'd do it.

- setters are called
     All attributes listed in attributeMap are applied to the DOM, and attributes for which there are custom setters (see :ref:`attributes <quickstart/writingWidgets/attributes>`, those custom setters are called

- postCreate
   This is typically the workhorse of a custom widget. The widget has been rendered (but note that sub-widgets in the containerNode have not!)

- startup
    If you need to be sure parsing and creation of any child widgets has completed, use startup.  This is often used for layout widgets like BorderContainer.

- destroy
     Implement destroy if you have special tear-down work to do (the superclasses will take care of most of it for you.


this.inherited()
----------------
In all cases its good practice to assume that you are overriding a method that may do something important in a class up the inheritance chain. So, call this.inherited() before or after your own code. E.g.

.. code-block :: javascript

  postCreate: function() {
     // do my stuff, then...
     this.inherited(arguments);
  }


Layout Widget Lifecycle
=======================
There are a few special things worth noting about layout widgets.

As listed above the startup() method is called after the widget has been inserted into the DOM and the children widgets have been initialized.  That's because those two conditions are necessary for the layout widget to do sizing.

Layout widgets are responsible for calling startup() and resize() on their children.   The parser has special code to detect which widgets are "top level" widgets, and which ones are children of layout widgets.  It only calls startup() on the top level widgets.

All of this is handled by the _LayoutWidget base class, see :ref:`dijit.layout._LayoutWidget <dijit/layout/_LayoutWidget>` but it's listed here in case you write a layout widget from scratch.

Note that :ref:`dijit.layout.ContentPane <dijit/layout/ContentPane>` functions as a layout widget even though it doesn't extend :ref:`dijit.layout._LayoutWidget <dijit/layout/_LayoutWidget>`.   The reason  it doesn't extend :ref:`dijit.layout._LayoutWidget <dijit/layout/_LayoutWidget>` is that :ref:`dijit.layout.ContentPane <dijit/layout/ContentPane>` is unusual in the sense that it's children are not directly beneath containerNode, but may be deeper in the DOM tree.
