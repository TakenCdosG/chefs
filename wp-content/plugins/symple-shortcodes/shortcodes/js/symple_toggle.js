jQuery(function($){$(document).ready(function(){$("h3.symple-toggle-trigger").click(function(){$(this).toggleClass("active").next().slideToggle("fast");return false;});});});