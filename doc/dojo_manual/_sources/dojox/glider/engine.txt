.. _dojox/glider/engine:

glider.engine
=============

:Status: Draft
:Version: beta
:Authors: Eugene Lazutkin, Robertus Harmawan Johansyah

=======
Summary
=======

glider.engine is namespace to describe that all class inside this namespace is rendering engine specific implementation. If you see the source code carefully, the way to make the abstraction to the other namespace is by defining class or object to glider.engine.*; for example, we want to make DrawingSystem available to the other so it is defined in dojox.glider.engine.DrawingSystem for all engine specific implementation.

==============
Engine Support
==============

At the moment, Glider supports only two specific rendering engine:

* Firefox Canvas3D

  Glider is able to run in specific Firefox version (3.0.*) with help of extension that Vladimir made. The extension is using OpenGL technology to draw 3D environment hence it needs Graphic Processing Unit (GPU) that has OpenGL implementation version 1.5 equal or above. He said that he may change the extension to support non-OpenGL implementation so that we do not need to have nice GPU. You can find his blog in `this link <http://blog.vlad1.com/2007/11/26/canvas-3d-gl-power-web-style/>`__ and if you want to download his extension, you can find in `this link <https://addons.mozilla.org/en-US/firefox/downloads/file/29359/canvas_3d__gecko_1.9_-0.2.0-fx.xpi>`__. At the moment, Glider is only making use of Firefox Canvas3D version 1.1 in order to support unification with Opera that does not have couple features that provided in version 2.0 such as Light.

* Opera Canvas3D

  In other hand, Glider also can be run in Opera 9.50 Alpha 1. Unfortunately, Opera is closed source project hence there is no much information but you still can find his blog in `this link <http://my.opera.com/timjoh/blog/2007/11/13/taking-the-canvas-to-another-dimension>`__ to get abstract API and example to use it.

======
Inside
======

glider.engine contains:

:ref:`firefox11 <dojox/glider/engine/firefox11>`
   :ref:`DrawingSystem <dojox/glider/engine/firefox11/DrawingSystem>`

   :ref:`TransformData <dojox/glider/engine/firefox11/TransformData>`

   :ref:`RenderData <dojox/glider/engine/firefox11/RenderData>`

   :ref:`Camera <dojox/glider/engine/firefox11/Camera>`

:ref:`opera <dojox/glider/engine/opera>`
   :ref:`DrawingSystem <dojox/glider/engine/opera/DrawingSystem>`

   :ref:`TransformData <dojox/glider/engine/opera/TransformData>`

   :ref:`RenderData <dojox/glider/engine/opera/RenderData>`

   :ref:`Camera <dojox/glider/engine/opera/Camera>`
