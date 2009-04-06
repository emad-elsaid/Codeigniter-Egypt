.. _dojox/glider:

dojox.glider
============

:Status: Draft
:Version: beta
:Authors: Eugene Lazutkin, Robertus Harmawan Johansyah

.. contents::
  :depth: 3

============
Introduction
============

Glider is a new experimental project that is able to display three-dimensional (3D) graphic in the browser. This project is coined after couple pioneer developer to introduce Canvas3D (or 3D Canvas) for Firefox and Opera; Dojo oversee this "breaking invention" as result Glider is born.

====
Goal
====

If you see Application Programming Interface (API) that Firefox and Opera provides, they contain a lot of function that hard for web developer to use. Not to mention, they are from two different browser and they have no intention to have sort of agreement to have same set of API. Based on those facts, Glider has couple goals as described in below.

-----------
Unification
-----------

Glider shall provide unified JavaScript (JS) API that uses any of Canvas3D implementations. At the moment, there are couple different implementation of Canvas3D, which are Firefox Canvas3D 1.1, Firefox Canvas3D 2.0 (addition to version 1.1 which supports shader features) and Opera Canvas3D. They have different API to be used and it would be painful to developer to provide different set for each version of Canvas3D. The great benefit of this feature to developer is able to display what they developed using Glider in different 3D drawing engine of Canvas3D with minimum changes in code and graphics.

-------------
User friendly
-------------

Another feature from Glider is providing a useful set of helpers for developer. The current API of Canvas3D libraries (both Firefox and Opera) are too hard to be used (too many low level graphic functions). Hence, Glider shall provide a set of handy functions includes:

- Easy environment setup.
- Rich shape builder (such as box, cones and loading 3D model files).
- Flexible view manipulation.
- Easy interaction between GFX 2.0 and outside environment.

----------------
High Performance
----------------
Glider shall consider performance as well since it needs to be run in high speed to reduce glitch effect. Some point that can be considered includes:

- Optimized way to compress and decompress core library (in JS).
- Optimized way to calculate transformations (camera or object transformation).
- Optimized way to know when Glider needs to render the environment (for example, it does not render when no state changes includes camera movement).
- How to make use of server memory (if it is available) called GPU (Graphic Processing Unit) memory or VRAM (Video Random Access Memory).

=========
Component
=========

Glider consists of many small component in order to work properly. The component below is organized according to the actual implementation namespace.

:ref:`glider <dojox/glider>`
 :ref:`engine <dojox/glider/engine>`
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

 :ref:`event <dojox/glider/event>`
  :ref:`Event <dojox/glider/event/Event>`

  :ref:`EventSystem <dojox/glider/event/EventSystem>`

 :ref:`loader <dojox/glider/loader>`
  :ref:`ObjLoader <dojox/glider/loader/ObjLoader>`

  :ref:`LoaderSystem <dojox/glider/loader/LoaderSystem>`

 :ref:`log <dojox/glider/log>`
  :ref:`LogSystem <dojox/glider/log/LogSystem>`
  
 :ref:`object <dojox/glider/object>`
  :ref:`BuilderSystem <dojox/glider/object/BuilderSystem>`
   
  :ref:`Camera <dojox/glider/object/Camera>`
   
  :ref:`DrawingSystem <dojox/glider/object/DrawingSystem>`
   
  :ref:`RenderData <dojox/glider/object/RenderData>`
   
  :ref:`RenderObject <dojox/glider/object/RenderObject>`
   
  :ref:`Synchronizeable <dojox/glider/object/Synchronizeable>`
   
  :ref:`TransformData <dojox/glider/object/TransformData>`

 :ref:`scene <dojox/glider/scene>`
  :ref:`ListScene <dojox/glider/scene/ListScene>`
  
  :ref:`Scene <dojox/glider/scene/Scene>`
  
  :ref:`SceneSystem <dojox/glider/scene/SceneSystem>`
  
 :ref:`util <dojox/glider/util>`
  :ref:`Array <dojox/glider/util/Array>`
  
  :ref:`Matrix <dojox/glider/util/Array>`
  
  :ref:`Quaterion <dojox/glider/util/Array>`
  
  :ref:`Vector3 <dojox/glider/util/Array>`

 :ref:`Driver <dojox/glider/Driver>`

If you want to see how the Glider components are loaded up (the bootstrapper) then go to :ref:`GliderBootStrap <GliderBootStrap>`.

=======
Diagram
=======

Class and sequence diagram are supplied in order to understand better about Glider implementation.

* Class Diagram

 * :ref:`Simple View <dojox/glider/SimpleClassDiagram>`
 * :ref:`Detail View <dojox/glider/DetailClassDiagram>`

* Sequence Diagram

 * :ref:`Initialize System <dojox/glider/InitSystemDiagram>`
 * :ref:`Initialize Scene <dojox/glider/InitSceneDiagram>`
 * :ref:`Create RenderObject <dojox/glider/CreateRObjectDiagram>`
 * :ref:`Draw Scene <dojox/glider/DrawSceneDiagram>`

=====
Usage
=====

------------------
Real World Example
------------------

There are couple example that uses Glider library.

- Viewer

This example uses Glider to display a 3D model in the web. User can modify the view by sliding or rotating the camera. At the moment, it only support OBJ format that is exported from Blender (see :ref:`Supported3DModelFormat <Supported3DModelFormat>`).

Link of the page is in `Viewer <http://robertus.dojotoolkit.org/viewer>`_.

- Furniture

This example is innovation for furniture business. Currently, many furniture website use boring 2D image to show their catalog; with Glider library, there is no more 2D image anymore, instead it loads 3D furniture model and display it to the customer. This practice will allow customer to see the model in very high detail. It will not only limited to displaying the 3D model but it should allow customer to design their own house by moving around the 3D model and stuff.

Link of the page is in `Furniture <http://robertus.dojotoolkit.org/furniture>`_.

--------
Tutorial
--------

In tutorial page, it will be explained how to use Glider library in great detail. Please go to :ref:`Tutorial <dojox/glider/Tutorial>` for detail information.
