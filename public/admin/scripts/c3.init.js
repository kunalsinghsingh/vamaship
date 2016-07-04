jQuery(function(){
	"use strict";function initC3Chart(){
		c3.generate({bindto:"#c3chartline",data:{columns:[["data1",30,200,100,200,150,250],["data2",50,20,10,40,15,25]]}})
		,c3.generate({bindto:"#c3chartspline",data:{columns:[["data1",30,200,100,400,150,250],["data2",130,100,140,200,150,50],["data3",50,20,10,40,15,25]],type:"spline"}})
		,c3.generate({bindto:"#c3chartstep",data:{columns:[["data1",300,350,300,0,0,100],["data2",130,100,140,200,150,50]],types:{data1:"step",data2:"area-step"}}})
		
		,c3.generate({bindto:"#c3chartbar",data:{columns:[["Inquiries",25,150,55,60,75,60],["Site Visits",5,25,12,8,15,5],["Bookings",3,11,2,2,1,1]],type:"bar"},bar:{width:{ratio:.5}}})
		
		
		,c3.generate({bindto:"#c3chartarea",data:{columns:[["data1",300,350,300,0,0,120],["data2",130,100,140,200,150,50]],types:{data1:"area-spline",data2:"area-spline"},groups:[["data1","data2"]]}})
		,c3.generate({bindto:"#c3chartstackedbar",data:{columns:[["data1",-30,200,200,400,-150,250],["data2",130,100,-100,200,-150,50],["data3",-230,200,200,-300,250,250]],type:"bar",groups:[["data1","data2"]]},grid:{y:{lines:[{value:0}]}}})
		,c3.generate({bindto:"#c3chartpie",data:{columns:[["setosa",.2,.2,.2,.2,.2,.4,.3,.2,.2,.1,.2,.2,.1,.1,.2,.4,.4,.3,.3,.3,.2,.4,.2,.5,.2,.2,.4,.2,.2,.2,.2,.4,.1,.2,.2,.2,.2,.1,.2,.2,.3,.3,.2,.6,.4,.3,.2,.2,.2,.2],["versicolor",1.4,1.5,1.5,1.3,1.5,1.3,1.6,1,1.3,1.4,1,1.5,1,1.4,1.3,1.4,1.5,1,1.5,1.1,1.8,1.3,1.5,1.2,1.3,1.4,1.4,1.7,1.5,1,1.1,1,1.2,1.6,1.5,1.6,1.5,1.3,1.3,1.3,1.2,1.4,1.2,1,1.3,1.2,1.3,1.3,1.1,1.3],["virginica",2.5,1.9,2.1,1.8,2.2,2.1,1.7,1.8,1.8,2.5,2,1.9,2.1,2,2.4,2.3,1.8,2.2,2.3,1.5,2.3,2,2,1.8,2.1,1.8,1.8,1.8,2.1,1.6,1.9,2,2.2,1.5,1.4,2.3,2.4,1.8,1.8,2.1,2.4,2.3,1.9,2.3,2.5,2.3,1.9,2,2.3,1.8]],type:"pie"}})
		,c3.generate({bindto:"#c3chartdonut",data:{columns:[["setosa",.2,.2,.2,.2,.2,.4,.3,.2,.2,.1,.2,.2,.1,.1,.2,.4,.4,.3,.3,.3,.2,.4,.2,.5,.2,.2,.4,.2,.2,.2,.2,.4,.1,.2,.2,.2,.2,.1,.2,.2,.3,.3,.2,.6,.4,.3,.2,.2,.2,.2],["versicolor",1.4,1.5,1.5,1.3,1.5,1.3,1.6,1,1.3,1.4,1,1.5,1,1.4,1.3,1.4,1.5,1,1.5,1.1,1.8,1.3,1.5,1.2,1.3,1.4,1.4,1.7,1.5,1,1.1,1,1.2,1.6,1.5,1.6,1.5,1.3,1.3,1.3,1.2,1.4,1.2,1,1.3,1.2,1.3,1.3,1.1,1.3],["virginica",2.5,1.9,2.1,1.8,2.2,2.1,1.7,1.8,1.8,2.5,2,1.9,2.1,2,2.4,2.3,1.8,2.2,2.3,1.5,2.3,2,2,1.8,2.1,1.8,1.8,1.8,2.1,1.6,1.9,2,2.2,1.5,1.4,2.3,2.4,1.8,1.8,2.1,2.4,2.3,1.9,2.3,2.5,2.3,1.9,2,2.3,1.8]],type:"donut"}})
		,$("#easypiechartDemo").easyPieChart({size:180,lineWidth:12,lineCap:"square",barColor:"#E91E63"})}
		
	function _init(){
		initC3Chart(),$(".site-head").find(".nav-trigger").on("click touchstart",function(){$(this).toggleClass("nav-expand"),".nav-wrap"==this&&$(this).toggleClass("nav-offcanvas"),setTimeout(function(){initC3Chart()})})}_init()});