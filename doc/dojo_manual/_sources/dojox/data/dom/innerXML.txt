.. _dojox/data/dom/innerXML:

=========
DELETE ME
=========

dojox.data.dom.innerXML
=======================

:Status: Draft
:Version: 1.0
:Project owner: ?--
:Available: since V?

.. contents::
   :depth: 2

Serializes a DOM XML document into its String representation.

============
Introduction
============

It is common to want to transmit an XML document.  It is also common to work with an XML document as a Document Object Model (DOM).  This function transforms a DOM object into its String representation.


=====
Usage
=====

.. code-block :: javascript

  var foo:String = dojox.data.dom.innerXML(node:Node);

A common usage of the XML string after creating it is to make an Ajax call using :ref:`xhrPut <dojo/xhrPut>` to send the resulting XML to the server.
