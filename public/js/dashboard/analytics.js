(function($) {
    /* "use strict" */
	
 var dzChartlist = function(){
	
	var screenWidth = $(window).width();
		var BarCharts = function(){
		 var options = {
          series: [{
          name: 'Beverages   <span class="value">569</span>',
          data: [30, 20, 30, 20, 20]
        }, {
          name: 'Food  <span class="value"> 1,567</span>',
          data: [40, 25, 40, 30, 25]
        }],
          chart: {
          type: 'bar',
          height: 230,
		  toolbar: {
					show: false
				},
        },
		grid: {	
			show: false
		},
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded',
			startingShape: "rounded",
			borderRadius: 7,
          },
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          show: true,
          width: 0,
          colors: ['transparent'],
		  lineCap: 'smooth',
        },
        xaxis: {
          categories: ['Week 01', 'Week 02', 'Week 03', 'Week 04', 'Week 05'],
		   labels: {
			show: true,
		   },
			 axisBorder:{
				   show: false,	
			 },
			 axisTicks: {
				show: false,
			},
        },
        yaxis: {
			show: true	,
			labels: {
			  show: true,
			  style: {
				  colors: [],
				  fontSize: '14px',
				  fontFamily: 'poppins',
				  fontWeight: 400,
				  cssClass: 'apexcharts-yaxis-label',
			  }
		  },
        },
		legend:{
			position: 'top',
			horizontalAlign: 'left', 
			fontWeight: 400,
			fontSize: '16px',
			fontFamily: 'poppins',
			itemMargin: {
			  horizontal: 15,
			  vertical: 5,
			  
			},
			 markers:{
				  radius: 12,
				  
			 },
		},
        fill: {
          opacity: 1
        },
		colors: ['#38E25D', '#216FED'],
        tooltip: {
          y: {
            formatter: function (val) {
              return "$ " + val + " thousands"
            }
          }
        }
        };

        var chart = new ApexCharts(document.querySelector("#BarCharts"), options);
        chart.render();
	}
	var donutChart1 = function(){	
		$("span.donut1").peity("donut", {
			width: "90",
			height: "90"
		});
	}
	var NewCustomers = function(){
		var options = {
		  series: [
			{
				name: 'Net Profit',
				data: [100,200, 100, 300, 200, 400],
				/* radius: 30,	 */
			}, 				
		],
			chart: {
			type: 'line',
			height: 50,
			width: 100,
			toolbar: {
				show: false,
			},
			zoom: {
				enabled: false
			},
			sparkline: {
				enabled: true
			}
			
		},
		
		colors:['#0E8A74'],
		dataLabels: {
		  enabled: false,
		},

		legend: {
			show: false,
		},
		stroke: {
		  show: true,
		  width: 6,
		  curve:'smooth',
		  colors:['#F43D3D'],
		},
		
		grid: {
			show:false,
			borderColor: '#eee',
			padding: {
				top: 0,
				right: 0,
				bottom: 0,
				left: 0

			}
		},
		states: {
                normal: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                hover: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                active: {
                    allowMultipleDataPointsSelection: false,
                    filter: {
                        type: 'none',
                        value: 0
                    }
                }
            },
		xaxis: {
			categories: ['Jan', 'feb', 'Mar', 'Apr', 'May'],
			axisBorder: {
				show: false,
			},
			axisTicks: {
				show: false
			},
			labels: {
				show: false,
				style: {
					fontSize: '12px',
				}
			},
			crosshairs: {
				show: false,
				position: 'front',
				stroke: {
					width: 1,
					dashArray: 3
				}
			},
			tooltip: {
				enabled: true,
				formatter: undefined,
				offsetY: 0,
				style: {
					fontSize: '12px',
				}
			}
		},
		yaxis: {
			show: false,
		},
		fill: {
		  opacity: 1,
		  colors:'#FB3E7A'
		},
		tooltip: {
			style: {
				fontSize: '12px',
			},
			y: {
				formatter: function(val) {
					return "$" + val + " thousands"
				}
			}
		},
		
		responsive: [{
			breakpoint: 1400,
			options: {
				chart: {
					height: 50,
					width: 70,
					toolbar: {
						show: false,
					},
					zoom: {
						enabled: false
					},
					sparkline: {
						enabled: true
					}
				},
			},
		}]

		};

		var chartBar1 = new ApexCharts(document.querySelector("#NewCustomers"), options);
		chartBar1.render();
	 
	}
	var Target = function(){
		var options = {
		  series: [
			{
				name: 'Net Profit',
				data: [100,200, 100, 300, 200, 400],
				//radius: 12,	
			}, 				
		],
			chart: {
			type: 'line',
			height: 50,
			width: 100,
			toolbar: {
				show: false,
			},
			zoom: {
				enabled: false
			},
			sparkline: {
				enabled: true
			}
			
		},
		
		colors:['#32D16D'],
		dataLabels: {
		  enabled: false,
		},

		legend: {
			show: false,
		},
		stroke: {
		  show: true,
		  width: 6,
		  curve:'smooth',
		  colors:['#32D16D'],
		},
		
		grid: {
			show:false,
			borderColor: '#eee',
			padding: {
				top: 0,
				right: 0,
				bottom: 0,
				left: 0

			}
		},
		states: {
                normal: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                hover: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                active: {
                    allowMultipleDataPointsSelection: false,
                    filter: {
                        type: 'none',
                        value: 0
                    }
                }
            },
		xaxis: {
			categories: ['Jan', 'feb', 'Mar', 'Apr', 'May'],
			axisBorder: {
				show: false,
			},
			axisTicks: {
				show: false
			},
			labels: {
				show: false,
				style: {
					fontSize: '12px',
				}
			},
			crosshairs: {
				show: false,
				position: 'front',
				stroke: {
					width: 1,
					dashArray: 3
				}
			},
			tooltip: {
				enabled: true,
				formatter: undefined,
				offsetY: 0,
				style: {
					fontSize: '12px',
				}
			}
		},
		yaxis: {
			show: false,
		},
		fill: {
		  opacity: 1,
		  colors:'#FB3E7A'
		},
		tooltip: {
			style: {
				fontSize: '12px',
			},
			y: {
				formatter: function(val) {
					return "$" + val + " thousands"
				}
			}
		},
		responsive: [{
			breakpoint: 1400,
			options: {
				chart: {
					height: 50,
					width: 70,
					toolbar: {
						show: false,
					},
					zoom: {
						enabled: false
					},
					sparkline: {
						enabled: true
					}
				},
			},
		}]
		};

		var chartBar1 = new ApexCharts(document.querySelector("#Target"), options);
		chartBar1.render();
	 
	}
	var SalesStatistic = function(){
		var options = {
		  series: [
			{
				name: 'Net Profit',
				data: [500,800, 400, 700, 500, 800, 300, 600,900,600,1000],
				//radius: 12,	
			}, 				
		],
			chart: {
			type: 'line',
			height: 300,
			toolbar: {
				show: false,
			},
			
		},
		
		colors:['#216FED'],
		dataLabels: {
		  enabled: false,
		},

		legend: {
			show: false,
		},
		stroke: {
		  show: true,
		  width: 8,
		  curve:'smooth',
		  colors:['#216FED'],
		},
		
		grid: {
			show:true,
			borderColor: '#AFAFAF',
			strokeDashArray: 5,
			padding: {
				top: 0,
				right: 0,
				bottom: 0,
				left: 0

			}
		},
		states: {
                normal: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                hover: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                active: {
                    allowMultipleDataPointsSelection: false,
                    filter: {
                        type: 'none',
                        value: 0
                    }
                }
            },
		xaxis: {
			categories: ['Jan', 'feb', 'Mar', 'Apr', 'May', 'Jun', 'Aug', 'Sep', 'Oct', 'Nov'],
			axisBorder: {
				show: true,
			},
			axisTicks: {
				show: false
			},
			labels: {
				style: {
					fontSize: '12px',
				}
			},
			crosshairs: {
				show: false,
				position: 'front',
				stroke: {
					width: 1,
					dashArray: 3
				}
			},
			tooltip: {
				enabled: true,
				formatter: undefined,
				offsetY: 0,
				style: {
					fontSize: '12px',
				}
			}
		},
		yaxis: {
			show: true,
			labels:{
				offsetX:-20,
			}
		},
		fill: {
		  opacity: 1,
		  colors:'#FB3E7A'
		},
		tooltip: {
			style: {
				fontSize: '12px',
			},
			y: {
				formatter: function(val) {
					return "$" + val + " thousands"
				}
			}
		}
		};

		var chartBar1 = new ApexCharts(document.querySelector("#SalesStatistic"), options);
		chartBar1.render();
	 
	}
	var SalesStatistic1 = function(){
		var options = {
		  series: [
			{
				name: 'Net Profit',
				data: [500,800, 400, 700, 500, 800, 300, 600,900,600,1000],
				//radius: 12,	
			}, 				
		],
			chart: {
			type: 'line',
			height: 300,
			toolbar: {
				show: false,
			},
			
		},
		
		colors:['#216FED'],
		dataLabels: {
		  enabled: false,
		},

		legend: {
			show: false,
		},
		stroke: {
		  show: true,
		  width: 8,
		  curve:'smooth',
		  colors:['#216FED'],
		},
		
		grid: {
			show:true,
			borderColor: '#AFAFAF',
			strokeDashArray: 5,
			padding: {
				top: 0,
				right: 0,
				bottom: 0,
				left: 0

			}
		},
		states: {
                normal: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                hover: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                active: {
                    allowMultipleDataPointsSelection: false,
                    filter: {
                        type: 'none',
                        value: 0
                    }
                }
            },
		xaxis: {
			categories: ['Jan', 'feb', 'Mar', 'Apr', 'May', 'Jun', 'Aug', 'Sep', 'Oct', 'Nov'],
			axisBorder: {
				show: true,
			},
			axisTicks: {
				show: false
			},
			labels: {
				style: {
					fontSize: '12px',
				}
			},
			crosshairs: {
				show: false,
				position: 'front',
				stroke: {
					width: 1,
					dashArray: 3
				}
			},
			tooltip: {
				enabled: true,
				formatter: undefined,
				offsetY: 0,
				style: {
					fontSize: '12px',
				}
			}
		},
		yaxis: {
			show: true,
			labels:{
				offsetX:-20,
			}
		},
		fill: {
		  opacity: 1,
		  colors:'#FB3E7A'
		},
		tooltip: {
			style: {
				fontSize: '12px',
			},
			y: {
				formatter: function(val) {
					return "$" + val + " thousands"
				}
			}
		}
		};

		var chartBar1 = new ApexCharts(document.querySelector("#SalesStatistic1"), options);
		chartBar1.render();
	 
	}
	var SalesStatistic2 = function(){
		var options = {
		  series: [
			{
				name: 'Net Profit',
				data: [500,800, 400, 700, 500, 800, 300, 600,900,600,1000],
				//radius: 12,	
			}, 				
		],
			chart: {
			type: 'line',
			height: 300,
			toolbar: {
				show: false,
			},
			
		},
		
		colors:['#216FED'],
		dataLabels: {
		  enabled: false,
		},

		legend: {
			show: false,
		},
		stroke: {
		  show: true,
		  width: 8,
		  curve:'smooth',
		  colors:['#216FED'],
		},
		
		grid: {
			show:true,
			borderColor: '#AFAFAF',
			strokeDashArray: 5,
			padding: {
				top: 0,
				right: 0,
				bottom: 0,
				left: 0

			}
		},
		states: {
                normal: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                hover: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                active: {
                    allowMultipleDataPointsSelection: false,
                    filter: {
                        type: 'none',
                        value: 0
                    }
                }
            },
		xaxis: {
			categories: ['Jan', 'feb', 'Mar', 'Apr', 'May', 'Jun', 'Aug', 'Sep', 'Oct', 'Nov'],
			axisBorder: {
				show: true,
			},
			axisTicks: {
				show: false
			},
			labels: {
				style: {
					fontSize: '12px',
				}
			},
			crosshairs: {
				show: false,
				position: 'front',
				stroke: {
					width: 1,
					dashArray: 3
				}
			},
			tooltip: {
				enabled: true,
				formatter: undefined,
				offsetY: 0,
				style: {
					fontSize: '12px',
				}
			}
		},
		yaxis: {
			show: true,
			labels:{
				offsetX:-20,
			}
		},
		fill: {
		  opacity: 1,
		  colors:'#FB3E7A'
		},
		tooltip: {
			style: {
				fontSize: '12px',
			},
			y: {
				formatter: function(val) {
					return "$" + val + " thousands"
				}
			}
		}
		};

		var chartBar1 = new ApexCharts(document.querySelector("#SalesStatistic2"), options);
		chartBar1.render();
	 
	}
	var SalesStatistic11 = function(){
		var options = {
		  series: [
			{
				name: 'Net Profit',
				data: [500,800, 400, 700, 500, 800, 300, 600,900,600,1000],
				//radius: 12,	
			}, 				
		],
			chart: {
			type: 'line',
			height: 300,
			toolbar: {
				show: false,
			},
			
		},
		
		colors:['#216FED'],
		dataLabels: {
		  enabled: false,
		},

		legend: {
			show: false,
		},
		stroke: {
		  show: true,
		  width: 8,
		  curve:'smooth',
		  colors:['#32D16D'],
		},
		
		grid: {
			show:true,
			borderColor: '#AFAFAF',
			strokeDashArray: 5,
			padding: {
				top: 0,
				right: 0,
				bottom: 0,
				left: 0

			}
		},
		states: {
                normal: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                hover: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                active: {
                    allowMultipleDataPointsSelection: false,
                    filter: {
                        type: 'none',
                        value: 0
                    }
                }
            },
		xaxis: {
			categories: ['Jan', 'feb', 'Mar', 'Apr', 'May', 'Jun', 'Aug', 'Sep', 'Oct', 'Nov'],
			axisBorder: {
				show: true,
			},
			axisTicks: {
				show: false
			},
			labels: {
				style: {
					fontSize: '12px',
				}
			},
			crosshairs: {
				show: false,
				position: 'front',
				stroke: {
					width: 1,
					dashArray: 3
				}
			},
			tooltip: {
				enabled: true,
				formatter: undefined,
				offsetY: 0,
				style: {
					fontSize: '12px',
				}
			}
		},
		yaxis: {
			show: true,
			labels:{
				offsetX:-20,
			}
		},
		fill: {
		  opacity: 1,
		  colors:'#FB3E7A'
		},
		tooltip: {
			style: {
				fontSize: '12px',
			},
			y: {
				formatter: function(val) {
					return "$" + val + " thousands"
				}
			}
		}
		};

		var chartBar1 = new ApexCharts(document.querySelector("#SalesStatistic11"), options);
		chartBar1.render();
	 
	}
	var SalesStatistic12 = function(){
		var options = {
		  series: [
			{
				name: 'Net Profit',
				data: [500,800, 400, 700, 500, 800, 300, 600,900,600,1000],
				//radius: 12,	
			}, 				
		],
			chart: {
			type: 'line',
			height: 300,
			toolbar: {
				show: false,
			},
			
		},
		
		colors:['#216FED'],
		dataLabels: {
		  enabled: false,
		},

		legend: {
			show: false,
		},
		stroke: {
		  show: true,
		  width: 8,
		  curve:'smooth',
		  colors:['#32D16D'],
		},
		
		grid: {
			show:true,
			borderColor: '#AFAFAF',
			strokeDashArray: 5,
			padding: {
				top: 0,
				right: 0,
				bottom: 0,
				left: 0

			}
		},
		states: {
                normal: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                hover: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                active: {
                    allowMultipleDataPointsSelection: false,
                    filter: {
                        type: 'none',
                        value: 0
                    }
                }
            },
		xaxis: {
			categories: ['Jan', 'feb', 'Mar', 'Apr', 'May', 'Jun', 'Aug', 'Sep', 'Oct', 'Nov'],
			axisBorder: {
				show: true,
			},
			axisTicks: {
				show: false
			},
			labels: {
				style: {
					fontSize: '12px',
				}
			},
			crosshairs: {
				show: false,
				position: 'front',
				stroke: {
					width: 1,
					dashArray: 3
				}
			},
			tooltip: {
				enabled: true,
				formatter: undefined,
				offsetY: 0,
				style: {
					fontSize: '12px',
				}
			}
		},
		yaxis: {
			show: true,
			labels:{
				offsetX:-20,
			}
		},
		fill: {
		  opacity: 1,
		  colors:'#FB3E7A'
		},
		tooltip: {
			style: {
				fontSize: '12px',
			},
			y: {
				formatter: function(val) {
					return "$" + val + " thousands"
				}
			}
		}
		};

		var chartBar1 = new ApexCharts(document.querySelector("#SalesStatistic12"), options);
		chartBar1.render();
	 
	}
	var SalesStatistic23 = function(){
		var options = {
		  series: [
			{
				name: 'Net Profit',
				data: [200,700, 500, 700, 600, 800, 500, 600,900,600,1000],
				//radius: 12,	
			}, 				
		],
			chart: {
			type: 'line',
			height: 300,
			toolbar: {
				show: false,
			},
			
		},
		
		colors:['#216FED'],
		dataLabels: {
		  enabled: false,
		},

		legend: {
			show: false,
		},
		stroke: {
		  show: true,
		  width: 8,
		  curve:'smooth',
		  colors:['#32D16D'],
		},
		
		grid: {
			show:true,
			borderColor: '#AFAFAF',
			strokeDashArray: 5,
			padding: {
				top: 0,
				right: 0,
				bottom: 0,
				left: 0

			}
		},
		states: {
                normal: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                hover: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                active: {
                    allowMultipleDataPointsSelection: false,
                    filter: {
                        type: 'none',
                        value: 0
                    }
                }
            },
		xaxis: {
			categories: ['Jan', 'feb', 'Mar', 'Apr', 'May', 'Jun', 'Aug', 'Sep', 'Oct', 'Nov'],
			axisBorder: {
				show: true,
			},
			axisTicks: {
				show: false
			},
			labels: {
				style: {
					fontSize: '12px',
				}
			},
			crosshairs: {
				show: false,
				position: 'front',
				stroke: {
					width: 1,
					dashArray: 3
				}
			},
			tooltip: {
				enabled: true,
				formatter: undefined,
				offsetY: 0,
				style: {
					fontSize: '12px',
				}
			}
		},
		yaxis: {
			show: true,
			labels:{
				offsetX:-20,
			}
		},
		fill: {
		  opacity: 1,
		  colors:'#FB3E7A'
		},
		tooltip: {
			style: {
				fontSize: '12px',
			},
			y: {
				formatter: function(val) {
					return "$" + val + " thousands"
				}
			}
		}
		};

		var chartBar1 = new ApexCharts(document.querySelector("#SalesStatistic23"), options);
		chartBar1.render();
	 
	}
	var chartTimeline111 = function(){
		
		var optionsTimeline = {
			chart: {
				type: "bar",
				height: 200,
				stacked: true,
				toolbar: {
					show: false
				},
				sparkline: {
					//enabled: true
				},
			},
			series: [
				 {
					name: "New Clients",
					data: [300, 450, 600, 600, 400, 450, 410, 470, 480, 700, 600, 800, 400, 600, 350, 250, 500, 550, 300, 400, 200]
				}
			],
			
			plotOptions: {
				bar: {
					columnWidth: "28%",
					endingShape: "rounded",
					startingShape: "rounded",
					borderRadius: 7,
					
					colors: {
						backgroundBarColors: ['#E9E9E9', '#E9E9E9', '#E9E9E9', '#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9'],
						backgroundBarOpacity: 1,
						backgroundBarRadius: 5,
					},

				},
				distributed: true
			},
			
			colors:['#216FED'],
			grid: {
				show: false,
			},
			legend: {
				show: false
			},
			fill: {
			  opacity: 1
			},
			dataLabels: {
				enabled: false,
				colors: ['#000'],
				dropShadow: {
				  enabled: true,
				  top: 1,
				  left: 1,
				  blur: 1,
				  opacity: 1
			  }
			},
			stroke:{
				 show: true,	
				 lineCap: 'rounded',
			},
			xaxis: {
			 categories: ['06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28'],
			  labels: {
			   style: {
				  colors: '#808080',
				  fontSize: '13px',
				  fontFamily: 'poppins',
				  fontWeight: 100,
				  cssClass: 'apexcharts-xaxis-label',
				},
			  },
			  crosshairs: {
				show: false,
			  },
			  axisBorder: {
				  show: false,
				},
			axisTicks:{
				  show: false,
			},
				
			},
			yaxis: {
				show: false,
				labels: {
				show: false,
				   style: {
					  colors: '#808080',
					  fontSize: '14px',
					   fontFamily: 'Poppins',
					  fontWeight: 100,
					  
					},
					 formatter: function (y) {
							  return y.toFixed(0) + "k";
							}
				  },
			},
			tooltip: {
				x: {
					show: true
				}
			},
			 responsive: [{
				breakpoint: 575,
				options: {
					chart: {
						height: 250,
					}
				}
			 }]
		};
		var chartTimelineRender =  new ApexCharts(document.querySelector("#chartTimeline111"), optionsTimeline);
		 chartTimelineRender.render();
	}
	var chartTimeline222 = function(){
		
		var optionsTimeline = {
			chart: {
				type: "bar",
				height: 200,
				stacked: true,
				toolbar: {
					show: false
				},
				sparkline: {
					//enabled: true
				},
			},
			series: [
				 {
					name: "New Clients",
					data: [300, 450, 600, 600, 400, 450, 410, 470, 480, 700, 600, 800, 400, 600, 350, 250, 500, 550, 300, 400, 200]
				}
			],
			
			plotOptions: {
				bar: {
					columnWidth: "28%",
					endingShape: "rounded",
					startingShape: "rounded",
					borderRadius: 7,
					
					colors: {
						backgroundBarColors: ['#E9E9E9', '#E9E9E9', '#E9E9E9', '#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9'],
						backgroundBarOpacity: 1,
						backgroundBarRadius: 5,
					},

				},
				distributed: true
			},
			
			colors:['#216FED'],
			grid: {
				show: false,
			},
			legend: {
				show: false
			},
			fill: {
			  opacity: 1
			},
			dataLabels: {
				enabled: false,
				colors: ['#000'],
				dropShadow: {
				  enabled: true,
				  top: 1,
				  left: 1,
				  blur: 1,
				  opacity: 1
			  }
			},
			stroke:{
				 show: true,	
				 lineCap: 'rounded',
			},
			xaxis: {
			 categories: ['06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28'],
			  labels: {
				   style: {
					  colors: '#808080',
					  fontSize: '13px',
					  fontFamily: 'poppins',
					  fontWeight: 100,
					  cssClass: 'apexcharts-xaxis-label',
					},
				  },
				  crosshairs: {
					show: false,
				  },
				  axisBorder: {
					  show: false,
					},
				axisTicks:{
					  show: false,
				},
				
			},
			yaxis: {
				show: false,
				labels: {
				show: false,
				   style: {
					  colors: '#808080',
					  fontSize: '14px',
					   fontFamily: 'Poppins',
					  fontWeight: 100,
					  
					},
					 formatter: function (y) {
							  return y.toFixed(0) + "k";
							}
				  },
			},
			tooltip: {
				x: {
					show: true
				}
			},
			 responsive: [{
				breakpoint: 575,
				options: {
					chart: {
						height: 250,
					}
				}
			 }]
		};
		var chartTimelineRender =  new ApexCharts(document.querySelector("#chartTimeline222"), optionsTimeline);
		 chartTimelineRender.render();
	}
	var chartTimeline333 = function(){
		
		var optionsTimeline = {
			chart: {
				type: "bar",
				height: 200,
				stacked: true,
				toolbar: {
					show: false
				},
				sparkline: {
					//enabled: true
				},
			},
			series: [
				 {
					name: "New Clients",
					data: [300, 450, 600, 600, 400, 450, 410, 470, 480, 700, 600, 800, 400, 600, 350, 250, 500, 550, 300, 400, 200]
				}
			],
			
			plotOptions: {
				bar: {
					columnWidth: "28%",
					endingShape: "rounded",
					startingShape: "rounded",
					borderRadius: 7,
					
					colors: {
						backgroundBarColors: ['#E9E9E9', '#E9E9E9', '#E9E9E9', '#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9','#E9E9E9'],
						backgroundBarOpacity: 1,
						backgroundBarRadius: 5,
					},

				},
				distributed: true
			},
			
			colors:['#216FED'],
			grid: {
				show: false,
			},
			legend: {
				show: false
			},
			fill: {
			  opacity: 1
			},
			dataLabels: {
				enabled: false,
				colors: ['#000'],
				dropShadow: {
				  enabled: true,
				  top: 1,
				  left: 1,
				  blur: 1,
				  opacity: 1
			  }
			},
			stroke:{
				 show: true,	
				 lineCap: 'rounded',
			},
			xaxis: {
			 categories: ['06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28'],
			  labels: {
			   style: {
				  colors: '#808080',
				  fontSize: '13px',
				  fontFamily: 'poppins',
				  fontWeight: 100,
				  cssClass: 'apexcharts-xaxis-label',
				},
			  },
			  crosshairs: {
				show: false,
			  },
			  axisBorder: {
				  show: false,
				},
			axisTicks:{
				  show: false,
			},
				
			},
			yaxis: {
				show: false,
				labels: {
				show: false,
				   style: {
					  colors: '#808080',
					  fontSize: '14px',
					   fontFamily: 'Poppins',
					  fontWeight: 100,
					  
					},
					 formatter: function (y) {
							  return y.toFixed(0) + "k";
							}
				  },
			},
			tooltip: {
				x: {
					show: true
				}
			},
			 responsive: [{
				breakpoint: 575,
				options: {
					chart: {
						height: 250,
					}
				}
			 }]
		};
		var chartTimelineRender =  new ApexCharts(document.querySelector("#chartTimeline333"), optionsTimeline);
		 chartTimelineRender.render();
	}
	var currentChart1 = function(){
		 var options = {
		  series: [85, 60, 67, 50],
		  chart: {
		  height: 330,
		  type: 'radialBar',
		},
		plotOptions: {
		  radialBar: {
				startAngle:-160,
			   endAngle: 160,
				dataLabels: {
			  name: {
				fontSize: '22px',
			  },
			  value: {
				fontSize: '16px',
			  },
			}
		  },
		},
		stroke:{
			 lineCap: 'round',
		},
		labels: ['Income', 'Income', 'Imcome', 'Income'],
		 colors:['#216FED', '#32D16D','#FF8723','#F43D3D'],
		};

		var chart = new ApexCharts(document.querySelector("#currentChart1"), options);
		chart.render();
	}
	var currentChart2 = function(){
		 var options = {
		  series: [85, 60, 67, 50],
		  chart: {
		  height: 330,
		  type: 'radialBar',
		},
		plotOptions: {
		  radialBar: {
				startAngle:-160,
			   endAngle: 160,
				dataLabels: {
			  name: {
				fontSize: '22px',
			  },
			  value: {
				fontSize: '16px',
			  },
			}
		  },
		},
		stroke:{
			 lineCap: 'round',
		},
		labels: ['Income', 'Income', 'Imcome', 'Income'],
		 colors:['#216FED', '#32D16D','#FF8723','#F43D3D'],
		};

		var chart = new ApexCharts(document.querySelector("#currentChart2"), options);
		chart.render();
	}
	var currentChart3 = function(){
		 var options = {
		  series: [85, 60, 67, 50],
		  chart: {
		  height: 330,
		  type: 'radialBar',
		},
		plotOptions: {
		  radialBar: {
				startAngle:-160,
			   endAngle: 160,
				dataLabels: {
			  name: {
				fontSize: '22px',
			  },
			  value: {
				fontSize: '16px',
			  },
			}
		  },
		},
		stroke:{
			 lineCap: 'round',
		},
		labels: ['Income', 'Income', 'Imcome', 'Income'],
		 colors:['#216FED', '#32D16D','#FF8723','#F43D3D'],
		};

		var chart = new ApexCharts(document.querySelector("#currentChart3"), options);
		chart.render();
	}
	var pieChart1 = function(){
		 var options = {
          series: [34, 12, 41, 22],
          chart: {
          type: 'donut',
		  height:250
        },
		dataLabels:{
			enabled: false
		},
		stroke: {
          width: 0,
        },
		colors:['#2BC155', '#FF7A30', '#FFEF5F', '#6418C3'],
		legend: {
              position: 'bottom',
			  show:false
            },
        responsive: [{
          breakpoint: 768,
          options: {
           chart: {
			  height:200
			},
          }
        }]
        };

        var chart = new ApexCharts(document.querySelector("#pieChart1"), options);
        chart.render();
    
	}
	
	
	/* Function ============ */
		return {
			init:function(){
			},
			
			
			load:function(){
				BarCharts();
				donutChart1();	
				NewCustomers();
				Target();
				SalesStatistic();
				SalesStatistic1();
				SalesStatistic2();
				SalesStatistic11();
				SalesStatistic12();
				SalesStatistic23();
				chartTimeline111();
				chartTimeline222();
				chartTimeline333();
				currentChart1();
				currentChart2();
				currentChart3();
				pieChart1();
			},
			
			resize:function(){
				
			}
		}
	
	}();

	jQuery(document).ready(function(){
	});
		
	jQuery(window).on('load',function(){
		setTimeout(function(){
			dzChartlist.load();
		}, 1000); 
		
	});

	jQuery(window).on('resize',function(){
		
		
	});     

})(jQuery);