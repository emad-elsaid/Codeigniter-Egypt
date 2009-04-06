.. _dojo/NodeList-fx:

dojo.NodeList-fx
================

:Status: Draft
:Version: 1.2


.. contents::
   :depth: 2

This module incorporates :ref:`dojo.fx` functionality into :ref:`dojo.query <dojo/query>` by extending the :ref:`dojo.NodeList <dojo/NodeList>` Class. 

The first most important thing to do is require the module into your page:

.. code-block :: javascript

  dojo.require("dojo.NodeList-fx");

To use your newly created functions in NodeList, issue a ``dojo.query()`` call:

.. cv-compound::

  .. cv:: html

     <button id="fadebutton">Fade Them Out</button> 
     <div id="fadebuttontarget">
        <li class="thinger">Item One</li>
        <li class="thinger">Item Two</li>
     </div>

  .. cv:: javascript

     <script type="text/javascript">
        dojo.require("dojo.NodeList-fx");
        dojo.addOnLoad(function(){
            dojo.query("#fadebutton").onclick(function(){
                dojo.query("#fadebuttontarget li").fadeOut().play();
            });
        });
     </script>

The most important thing to note is NodeList animations return an instance of a :ref:`dojo._Animation <Animation>`, the foundation for all Dojo FX. This prevents further chaining, as you have to explicitly call ``.play()`` on the returned animation.

The parameters you can specify to each animation provided are identical to their :ref:`dojo.fx` counterparts, omitting the ``node:`` parameter, as each node in the NodeList is passed for you.

There are more NodeList animations provided by the :ref:`dojox.fx.ext-dojo.NodeList <dojox/fx/NodeList>` module, in the :ref:`dojox.fx` project.

.. _dojo.fx: dojo/fx
.. _dojox.fx: dojox/fx
