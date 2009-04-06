.. _quickstart/writingWidgets/attributes:

Attributes
==========

How should a widget writer deal with attributes of the widget?

On the :ref:`previous page <quickstart/writingWidgets/templates>` we talked about substitution variables like ${title}.  The problem with using substitution variables in templates is that it doesn't let a developer '''change''' a value after a widget has been created.  It also doesn't allow attributes for which complex processing is needed (such as changing the CSS class of a widget depending on whether or not it's disabled).

To support those operations, you should use attributeMap and custom getters/setters.  This page documents how to do that.


Adding attributes to your widgets
---------------------------------

The first thing to think about as a widget developer is that all the documentation for an attribute needs to go next
to the attribute definition, even when you need special documentation about how attr() is performing for that
widget.

.. code-block :: javascript

  // value: Date
  //     The date picked on the date picker, as a Date Object.
  //     When setting the date on initialization (ex: new DateTextBox({value: "2008-1-1"})
  //     or changing it (ex: attr('value', "2008-1-1")), you  can specify either a Date object or
  //     a string in ISO format

The second thing is that when writing or extending a widget you need to think of a "holy trinity" for each widget attribute:

- initialization
- setter
- getter

Some attributes can only be specified at initialization time, but for most of them, they can be changed after initialization, and users can always get the value at any time. For example, you might have had a template
like this:

.. code-block :: html

  <button>${label}</button>

That's compact and support initialization with a label:

.. code-block :: javascript

  new myButtonWidget({label: 'hi'})

However, this '''doesn't''' work:

.. code-block :: javascript

  myButtonWidget.attr('label', 'bye')



attributeMap
------------
So, instead, you should be supporting this through attributeMap.

attributeMap is an attribute inside of widgets.
You should think of attributeMap as a binding from widget attribute to DOM nodes. It
can map widget attributes to DOM node attributes, and it can map to
innerHTML (like above) too.

You can see this in action for TitlePane:

.. code-block :: javascript

	attributeMap: dojo.delegate(dijit.layout.ContentPane.prototype.attributeMap, {
		title: { node: "titleNode", type: "innerHTML" }
	}),

(the fancy mixin stuff is so TitlePane's attributeMap has everything that ContentPane has,
plus this additional command).

To map a widget attribute to a DOM node attribute, you do:

.. code-block :: javascript

  attributeMap: {
        disabled: {node: "focusNode", type: "attribute" }
  }),

or alternately just

.. code-block :: javascript

  attributeMap: {
        disabled: "focusNode"
  }),

Both code blocks copy the widget's "disabled" attribute onto the focusNode DOM node in the template.

attributeMap also supports class attributes like iconClass.  See dijit.Menu for an example of all of these in action:

.. code-block :: javascript

	attributeMap: dojo.delegate(dijit._Widget.prototype.attributeMap, {
		label: { node: "containerNode", type: "innerHTML" },
		iconClass: { node: "iconNode", type: "class" },
		disabled: {node: "focusNode", type: "attribute" }
	}),


Custom setters/getters
----------------------

When you have an attribute where setting/getting it is more complicated than attributeMap can
handle, you need to write custom getters/setters for it. The naming convention is _setFooAttr() and
_getFooAttr(). attr() will automatically detect and call these custom setters.

Custom setters are quite common. Usually you don't need a custom getter (as the default action
for attr('foo') is to access Widget.foo), but for something like Editor where it's impractical to constantly
keep Editor.value up to date, writing a custom _getValueAttr() accessor makes sense.

Life cycle
----------
The custom setters listed above, plus every attribute listed in attributeMap, is applied during
widget creation (in addition to whenever someone calls attr('name', value)).

Note that the application happens after buildRendering() but before postCreate(), so
you need to make sure that none of that code is dependent on something that happens
in postCreate(), or later. This in particular is an issue for any widgets that depend on timeouts
for setup, which need to have special code to handle when _setDisabledAttr() etc. is
called during startup.
