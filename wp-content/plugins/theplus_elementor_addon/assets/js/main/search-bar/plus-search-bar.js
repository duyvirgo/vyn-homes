/*Search Bar*/
(function($) {
    "use strict";
    var WidgetSearchBarHandler = function($scope, $) {
        var container = $scope[0].querySelectorAll('.tp-search-bar'),
            main = $('.tp-search-bar', $scope),
            ajaxsearch = main.data("ajax_search"),
            acfData = main.data("acfdata"),
            Generic = main.data("genericfilter"),
            resultsinnerList = main.find('.tp-search-list-inner'),
            searcharea = main.find('.tp-search-area'),
            resultList = '.tp-search-slider tp-row',
            searchheader = main.find('.tp-search-header'),
            Rsetting = (container[0].dataset) ? JSON.parse(container[0].dataset.resultSetting) : [],
            pagesetting = (container[0].dataset) ? JSON.parse(container[0].dataset.paginationData) : [],
            Defsetting = (container[0].dataset) ? JSON.parse(container[0].dataset.defaultData) : [];
            resultsinnerList.css('transform', 'translateX(0)');
          
        let OverlayBg = container[0].querySelectorAll('.tp-rental-overlay');
            if( OverlayBg.length > 0 ){
                tp_overlay_body($scope);
            }

        let GetDropDown = container[0].querySelectorAll('.tp-sbar-dropdown');
            if(GetDropDown.length > 0){
                $('.tp-sbar-dropdown', $scope).on('click',function () {
                    $(this).attr('tabindex', 1).focus();
                    $(this).toggleClass('active');
                    $(this).find('.tp-sbar-dropdown-menu').slideToggle(300);
                });
                $('.tp-sbar-dropdown', $scope).focusout(function () {
                    $(this).removeClass('active');
                    $(this).find('.tp-sbar-dropdown-menu').slideUp(300);
                });
                $('.tp-sbar-dropdown .tp-sbar-dropdown-menu .tp-searchbar-li', $scope).on('click',function () {
                    $(this).parents('.tp-sbar-dropdown').find('span').text($(this).text());
                    $(this).parents('.tp-sbar-dropdown').find('input').attr('value', $(this).attr('id')).change();
                });
            }

        if(ajaxsearch.ajax == 'yes'){
            var $NavigationOn = (pagesetting.PNavigation) ? pagesetting.PNavigation : 0,
                $PostPer = (ajaxsearch && ajaxsearch.post_page) ? ajaxsearch.post_page : 3,
                $Searhlimit = (ajaxsearch && ajaxsearch.ajaxsearchCharLimit) ? ajaxsearch.ajaxsearchCharLimit : 3;

            var timeoutID = null;
            $(container[0]).on("change keyup", function(e){
                let EvnetType = e.type,
                    serText = $("input[name=s]", $scope).val(),
                    post = $("input[name=post_type]", $scope).val();

                if( (EvnetType == 'keyup' && serText.length > $Searhlimit) 
                    || (EvnetType == 'change' && ((post) || (post && serText.length > $Searhlimit) || (post=='' && serText.length > $Searhlimit)) ) ){

                    container[0].querySelector('.tp-close-btn').style.cssText = "display:none";
                    container[0].querySelector('.tp-ajx-loading').style.cssText = "display:flex";

                    clearTimeout(timeoutID);
                    timeoutID = setTimeout(tp_widget_searchbar.bind(undefined, e.target.value), 400);
                }else{                   
                    let closeBtn = container[0].querySelectorAll('.tp-close-btn');                    
                }
            });

            function tp_widget_searchbar(search) {
                let serText = $("input[name=s]", $scope).val(),
                    tax = $("input[name=taxonomy]", $scope).val(),
                    post = $("input[name=post_type]", $scope).val();
                    resultsinnerList.html('');
                    searchheader.find('.tp-search-pagina', $scope).html('');

                let AjaxData = {
                        action : 'tp_search_bar',
                        searchData : $('.tp-search-form', $scope).serialize(),
                        text : serText,
                        postper : ajaxsearch.post_page,
                        GFilter : Generic,
                        ACFilter : acfData,
                        ResultData : pagesetting,
                        DefaultData : Defsetting,
                        nonce : ajaxsearch.nonce,
                    };

                $.ajax({
                    url: theplus_ajax_url,
                    method: 'post',
                    async: false,
                    data: AjaxData,  
                    beforeSend: function() {
                    },
                    success: function(response) {
                        let ErrorHtml = container[0].querySelectorAll('.tp-search-error'),
                            Headerclass = container[0].querySelector('.tp-search-header');

                        if(response.data.error && ErrorHtml.length > 0){
                            ErrorHtml[0].innerHTML = response.data.message;
                            Headerclass.style.cssText = "display:none";
                            searcharea.slideDown(100);
                            return;
                        }else{
                            Headerclass.style.cssText = "display:flex";
                            ErrorHtml[0].innerHTML = '';
                        }

                        var responseData = response.data;
                        if (responseData && responseData.post_count !== 0) {
                            let posts = responseData.posts,
                                post = null,
                                outputHtml ='',
                                listHtml = '<div class="' + resultList.replace('.','') + '">%s</div>',
                                listItemHtml ='';
                                searcharea.slideDown(100);
                        
                                for (post in posts) {
                                    listItemHtml += getSearchhtml(posts[post]);

                                    if((parseInt(post) + 1) % responseData.limit_query == 0 || parseInt(post) === posts.length - 1) {
                                        outputHtml += listHtml.replace('%s', listItemHtml);
                                        listItemHtml = '';
                                    }
                                }
                                
                                resultsinnerList.html(outputHtml);

                                if(Rsetting.TotalResult){
                                    searchheader.find('.tp-search-resultcount').html(responseData.post_count + " Results ")
                                }
                                if(responseData.pagination) {
                                    container[0].querySelector('.tp-search-pagina').innerHTML = responseData.pagination;
                                    tp_pagination_ajax(resultList, resultsinnerList, responseData, AjaxData)
                                }else if(responseData.loadmore) {
                                    container[0].querySelector('.ajax_load_more').innerHTML = responseData.loadmore;
                                    tp_loadmore_ajax(resultList, resultsinnerList, responseData, AjaxData)
                                }else if(responseData.lazymore) {
                                    container[0].querySelector('.ajax_lazy_load').innerHTML = responseData.lazymore;
                                    tp_lazymore_ajax(resultList, resultsinnerList, responseData, AjaxData)
                                }
                        }else{
                            ErrorHtml[0].innerHTML = Rsetting.errormsg;
                            Headerclass.style.cssText = "display:none";
                            searcharea.slideDown(400);
                            return;
                        }
                    },
                    complete: function() {
                    },
                }).then(function(e) {
                    setTimeout(function(){ 
                        container[0].querySelector('.tp-ajx-loading').style.cssText = "display:none";
                        container[0].querySelector('.tp-close-btn').style.cssText = "display:flex";
                    }, 500);                        
                    tp_Close_result()
                });
            }

        }

        var getSearchhtml = function(data) {
            let output = '',
                Title = (data.title) ? data.title : '',
                Content = (data.content) ? data.content : '';
                
                if(Rsetting.textlimit){
                    if(Rsetting.TxtTitle){
                        let txtCount = (Rsetting.textcount) ? Rsetting.textcount : 100,
                            txtdot = (Rsetting.textdots) ? Rsetting.textdots : '';
                        if(Rsetting.texttype == "char"){
                            Title = Title.substring(0, txtCount) + txtdot; 
                        }else if(Rsetting.texttype == "word"){
                            Title = Title.split(" ", txtCount).toString().replace(/,/g, " ") + txtdot;
                        }
                    }

                    if(Rsetting.Txtcont){
                        let contcount = (Rsetting.ContCount) ? Rsetting.ContCount : 100,
                            txtdotc = (Rsetting.ContDots) ? Rsetting.ContDots : '';
                        if(Rsetting.ContType == "char"){
                            Content = Content.substring(0, contcount) + txtdotc;
                        }else if(Rsetting.ContType == "word"){
                            Content = Content.split(" ", contcount).toString().replace(/,/g, " ") + txtdotc;
                        }
                    }
                }

            output += '<div class="tp-ser-item '+ajaxsearch.styleColumn+'">';
                output += '<a class="tp-serpost-link" href='+ data.link +' target="_black" >';

                    if(Rsetting.ONThumb && data.thumb){
                        output += '<div class="tp-serpost-thumb">';
                            output += '<img class="tp-item-image" src=' + (data.thumb != '' ? data.thumb : 'http://localhost/wordpress/wp-content/uploads/2020/05/placeholder.png') + '>';
                        output += '</div>';
                    }

                    output += '<div class="tp-serpost-wrap">';
                        if( (Rsetting.ONTitle && Title) || (Rsetting.ONPrice && data.Wo_Price) ){
                            output += '<div class="tp-serpost-inner-wrap">';
                                if(Rsetting.ONTitle && Title){
                                    output += '<div class="tp-serpost-title">'+Title+'</div>';
                                }
                                if(Rsetting.ONPrice && data.Wo_Price){
                                    output += '<div class="tp-serpost-price">'+data.Wo_Price+'</div>';
                                }
                            output += '</div>';
                        }
                        if(Rsetting.ONContent && Content){
                            output += '<div class="tp-serpost-excerpt">'+Content+'</div>';
                        }
                        if(Rsetting.ONShortDesc && data.Wo_shortDesc){
                            output += '<div class="tp-serpost-shortDesc">'+ data.Wo_shortDesc +'</div>';
                        }
                    output += '</div>';
                output += '</a>';
            output += '</div>';
        return output;
        };

        var tp_loadmore_ajax = function(listHtml, innerlist, responseData, ajaxData) {
            let loadclass = container[0].querySelectorAll('.post-load-more'),
                Postclass = container[0].querySelector('.tp-search-slider'),
                Paginaclass = container[0].querySelectorAll('.tp-search-pagina');
                
                if(Paginaclass.length > 0){
                    Paginaclass[0].innerHTML = responseData.loadmore_page;
                }

                if(loadclass.length > 0){
                    loadclass[0].addEventListener("click", function(e){
                        let PageNum = Number(this.dataset.page),
                            NewNum = Number(PageNum + 1),
                            PostCount = container[0].querySelectorAll('.tp-ser-item');
                            ajaxData.offset = PostCount.length;
                            ajaxData.loadNumpost = pagesetting.loadnumber;

                            jQuery.ajax({
                                url: theplus_ajax_url,
                                method: 'post',
                                async: false,
                                data: ajaxData,
                                beforeSend: function() {
                                    loadclass[0].textContent = pagesetting.loadingtxt;
                                },
                                success: function (loadRes) {
                                    loadclass[0].textContent = pagesetting.loadbtntxt;
                                    let posts = loadRes.data.posts,
                                        totalcount = loadRes.data.total_count,                                
                                        post = null,
                                        listItemHtml ='';

                                        for(post in posts){
                                            listItemHtml += getSearchhtml(posts[post]);
                                        }
                                       
                                        $(Postclass).append(listItemHtml);
                                        loadclass[0].setAttribute("data-page", NewNum);

                                        if(Paginaclass.length > 0){
                                            let PageCount = Paginaclass[0].querySelectorAll('.tp-load-number')
                                                PageCount[0].textContent = NewNum;
                                        }

                                        let postscount = container[0].querySelectorAll('.tp-ser-item');
                                        if(postscount.length == totalcount){
                                            loadclass[0].classList.add('hide');
                                            $(loadclass[0].parentNode).append('<div class="plus-all-posts-loaded">'+pagesetting.loadedtxt+'</div>')
                                        }
                                },
                                complete: function() {
                                },
                            });
                    });
                }
        }

        var tp_lazymore_ajax = function(listHtml, innerlist, responseData, ajaxData) {
            let loadclass = container[0].querySelectorAll('.post-lazy-load'),
                Postclass = container[0].querySelector('.tp-search-slider'),
                Paginaclass = container[0].querySelectorAll('.tp-search-pagina');

            var windowWidth, windowHeight, documentHeight, scrollTop, containerHeight, containerOffset, $window = $(window);
            var recalcValues = function() {
                windowWidth = $window.width();
                windowHeight = $window.height();
                documentHeight = $('body').height();
                containerHeight = $(".tp-search-area").height();
                containerOffset = $(".tp-search-area").offset().top + 50;
                setTimeout(function() {
                    containerHeight = $(".tp-search-area").height();
                    containerOffset = $(".tp-search-area").offset().top + 50;
                }, 50);
            };
                recalcValues();
                $window.resize(recalcValues);

            $window.bind('scroll', function(e) {
                e.preventDefault();
                    recalcValues();
                    scrollTop = $window.scrollTop();
                    containerHeight = $(".tp-search-area").height();
                    containerOffset = $(".tp-search-area").offset().top + 50;

                    var lazyFeed_click = $(".tp-search-area").find(".post-lazy-load"),
                        PostCount = container[0].querySelectorAll('.tp-ser-item');
                        ajaxData.offset = PostCount.length;
                        ajaxData.loadNumpost = pagesetting.loadnumber;

                        if ($(".tp-search-area").find(".post-lazy-load").length && scrollTop < documentHeight && (scrollTop + 60 > (containerHeight + containerOffset - windowHeight))) {
                                if (lazyFeed_click.data('requestRunning')) {
                                    return;
                                }
                                    lazyFeed_click.data('requestRunning', true);

                                jQuery.ajax({
                                    url: theplus_ajax_url,
                                    method: 'post',
                                    async: false,
                                    data: ajaxData,
                                    beforeSend: function() {
                                    },
                                    success: function (loadRes) {
                                        let posts = loadRes.data.posts, 
                                            totalcount = loadRes.data.total_count,
                                            post = null,
                                            listItemHtml ='';

                                            for(post in posts){
                                                listItemHtml += getSearchhtml(posts[post]);
                                            }
                                            
                                            $(Postclass).append(listItemHtml);

                                            let postscount = container[0].querySelectorAll('.tp-ser-item');
                                            if(postscount.length == totalcount){
                                                loadclass[0].classList.add('hide');
                                                $(loadclass[0].parentNode).append('<div class="plus-all-posts-loaded">'+pagesetting.loadedtxt+'</div>')
                                            }
                                    },
                                    complete: function() {
                                        lazyFeed_click.data('requestRunning', false);
                                    },
                                });
                        }
            });

        }

        var tp_pagination_ajax = function(listHtml, innerlist, responseData, ajaxData) {
            let Innerclass = container[0].querySelector('.tp-search-list-inner'),
                Buttonajax = container[0].querySelectorAll('.tp-pagelink.tp-ajax-page'),
                NextBtn = container[0].querySelectorAll('.tp-pagelink.next'),
                PrevBtn = container[0].querySelectorAll('.tp-pagelink.prev'),
                $counterOn = (pagesetting.Pcounter) ? pagesetting.Pcounter : 0,
                $Countlimit = (pagesetting.PClimit) ? pagesetting.PClimit : 3;

                if(Buttonajax.length > 0){
                    Buttonajax.forEach(function(self,idx) {
                        if(Number(self.dataset.page) == Number(1)){
                            let Findhtml = container[0].querySelectorAll('.tp-search-slider');
                                if(Findhtml.length > 0){
                                    Findhtml[0].classList.add( 'ajax-'+Number(1) );
                                }
                        }else{
                            $(Innerclass).append('<div class="tp-search-slider tp-row ajax-'+ Number(idx+1) +'"></div>');
                        }

                        self.addEventListener("click", function(e){
                            let PageNumber = this.dataset.page,
                                Offset = (PageNumber * $PostPer) - ($PostPer),
                                Position = idx*100;
                                ajaxData.offset = Offset;

                                tp_pagination_active(Buttonajax,PageNumber)

                                if($NavigationOn){
                                    PrevBtn[0].setAttribute("data-prev", PageNumber);
                                    NextBtn[0].setAttribute("data-next", PageNumber);
                                }

                                let ajaxclass = Innerclass.querySelectorAll('.tp-search-slider.ajax-'+PageNumber);
                                    if(ajaxclass.length > 0){
                                        if(ajaxclass[0].querySelector('.tp-ser-item')){
                                            Innerclass.style.cssText = "transform: translateX("+ -(Position) +"%)";
                                            tp_pagination_hidden(responseData);
                                            return;
                                        }
                                    }

                                jQuery.ajax({
                                    url: theplus_ajax_url,
                                    method: 'post',
                                    async: false,
                                    data: ajaxData,
                                    beforeSend: function() {
                                    },
                                    success: function (res2) {
                                        let posts = res2.data.posts,
                                            post = null,
                                            listItemHtml ='';

                                            for(post in posts){
                                                listItemHtml += getSearchhtml(posts[post]);
                                            }

                                            $(ajaxclass[0]).append(listItemHtml);
                                            Innerclass.style.cssText = "transform: translateX("+ -(Position) +"%)";
                                            tp_pagination_hidden(responseData);
                                    },
                                    complete: function() {
                                    },
                                });
                        });
                    });
                }

                if(NextBtn.length > 0){
                    NextBtn[0].addEventListener("click", function(e){
                        let PageNumber = Number(this.dataset.next),
                            NewNumber = PageNumber + Number(1),
                            Position = -(PageNumber * Number(100)),
                            Offset = (NewNumber * $PostPer) - ($PostPer);
                            ajaxData.offset = Offset;

                            if($counterOn){
                                Buttonajax.forEach(function(self,idxi) {
                                    if(NewNumber == Number(self.dataset.page)){   
                                        if(self.classList.contains('hide')){
                                            let one = Number(idxi+1 - $Countlimit);
                                                self.classList.remove('hide');
                                                Buttonajax.forEach(function(self,idxii) {
                                                    if(one == idxii+1){
                                                        self.classList.add('hide');
                                                    }
                                                });
                                        }
                                    }
                                });
                            }

                            tp_pagination_active(Buttonajax,NewNumber)

                            if($NavigationOn){
                                PrevBtn[0].setAttribute("data-prev", NewNumber);
                                NextBtn[0].setAttribute("data-next", NewNumber);
                            }

                            let ajaxclass = Innerclass.querySelectorAll('.tp-search-slider.ajax-'+NewNumber);
                                if(ajaxclass.length > 0){
                                    if(ajaxclass[0].querySelector('.tp-ser-item')){
                                        Innerclass.style.cssText = "transform: translateX("+ Position +"%)";
                                        tp_pagination_hidden(responseData);
                                        return;
                                    }
                                }

                                jQuery.ajax({
                                    url: theplus_ajax_url,
                                    method: 'post',
                                    async: false,
                                    data: ajaxData,
                                    beforeSend: function() {
                                    },
                                    success: function (nextres) {
                                        let posts = nextres.data.posts,
                                            post = null,
                                            listItemHtml ='';

                                            for(post in posts){
                                                listItemHtml += getSearchhtml(posts[post]);
                                            }

                                            $(ajaxclass[0]).append(listItemHtml);
                                            Innerclass.style.cssText = "transform: translateX("+ Position +"%)";
                                            tp_pagination_hidden(responseData);
                                    },
                                    complete: function() {
                                    },
                                });
                    });
                }
                
                if(PrevBtn.length > 0){
                    PrevBtn[0].addEventListener("click", function(e){
                        let PageNumber = Number(this.dataset.prev),
                            OldNumber = PageNumber - Number(1),
                            Position = -(OldNumber * 100) + 100,
                            Offset = (OldNumber * $PostPer) - ($PostPer);
                            ajaxData.offset = Offset;

                            if($counterOn){
                                Buttonajax.forEach(function(self,idxi) {
                                    if(OldNumber == Number(self.dataset.page)){   
                                        if(self.classList.contains('hide')){
                                            let one = Number( idxi+1 + ($Countlimit) );
                                                self.classList.remove('hide');
                                                Buttonajax.forEach(function(self,idxii) {
                                                    if(one == idxii+1){
                                                        self.classList.add('hide');
                                                    }
                                                });
                                        }
                                    }
                                });
                            }

                            tp_pagination_active(Buttonajax,OldNumber)

                            if($NavigationOn){
                                PrevBtn[0].setAttribute("data-prev", OldNumber);
                                NextBtn[0].setAttribute("data-next", OldNumber);
                            }

                            let ajaxclass = Innerclass.querySelectorAll('.tp-search-slider.ajax-'+OldNumber);
                                if(ajaxclass.length > 0){
                                    if(ajaxclass[0].querySelector('.tp-ser-item')){
                                        Innerclass.style.cssText = "transform: translateX("+ Position +"%)";
                                        tp_pagination_hidden(responseData);
                                        return;
                                    }
                                }

                                jQuery.ajax({
                                    url: theplus_ajax_url,
                                    method: 'post',
                                    async: false,
                                    data: ajaxData,
                                    beforeSend: function() {
                                    },
                                    success: function (Prevres) {
                                        let posts = Prevres.data.posts,
                                            post = null,
                                            listItemHtml ='';

                                            for(post in posts){
                                                listItemHtml += getSearchhtml(posts[post]);
                                            }

                                            $(ajaxclass[0]).append(listItemHtml);
                                            Innerclass.style.cssText = "transform: translateX("+ Position +"%)";

                                            tp_pagination_hidden(responseData);
                                    },
                                    complete: function() {
                                    },
                                });


                    });
                }
        }

        var tp_pagination_hidden = function(responseData){
            if(responseData.columns){
                let Next = container[0].querySelector('.tp-pagelink.next').dataset.next;
                    if(parseInt(Next) == responseData.columns){
                       $('.tp-pagelink.next').hide();
                    }else{
                       $('.tp-pagelink.next').show();
                    }

                let Prev = container[0].querySelector('.tp-pagelink.prev').dataset.prev;
                    if(parseInt(Prev) == 1){
                        $('.tp-pagelink.prev').hide();
                    }else{
                        $('.tp-pagelink.prev').show();
                    }
            }
        }

        var tp_pagination_active = function($class, $val){
            if($class.length > 0){
                $class.forEach(function(item) {
                    if($val == Number(item.dataset.page)){
                        item.classList.add('active');
                    }else if(item.classList.contains('active')){
                        item.classList.remove('active');
                    }
                });
            }
        }
        
        var tp_Close_result = function() {
            let Area = container[0].querySelector('.tp-search-area'),
                input= container[0].querySelector('input[name=s]'),
                overlay = $scope[0].querySelectorAll('.tp-rental-overlay'),
                closebtn = container[0].querySelector('.tp-close-btn');

                $('.tp-close-btn', $scope).on('click', function() {
                    input.value='';
                    $(this).hide();
                    $(Area).slideUp();

                    if(overlay.length > 0){
                        overlay[0].style.cssText = "visibility:hidden;opacity:0;";
                    }
                })

                main.keyup(function(e) {
                    if (e.key === "Escape") {
                        input.value='';
                        $(Area).slideUp();
                        closebtn.style.cssText = "display:none";
                    }
                })

        }

    };

    function tp_overlay_body($scope){
        let overlay = $scope[0].querySelector('.tp-rental-overlay'),
            textbox = $scope[0].querySelector('.tp-input-field');

        // Input    
        $(".tp-search-input", $scope).on({
            focus: function () {
                overlay.style.cssText = "visibility:visible;opacity:1;";
                textbox.style.cssText = "z-index:1000;";
            },
            focusout: function () {
                overlay.style.cssText = "visibility:hidden;opacity:0;";
                textbox.style.cssText = "z-index:0;";
            }
        });

        // select 
        $(".tp-select", $scope).on('click', function(e){
            overlay.style.cssText = "visibility:visible;opacity:1;";
        })
        $(".tp-rental-overlay", $scope).on('click', function(e){
            overlay.style.cssText = "visibility:hidden;opacity:0;";
        });

        // Esc ket to close
        $scope.keyup(function(e) {
            if (e.key === "Escape") {
                overlay.style.cssText = "visibility:hidden;opacity:0;";
            }
        })
    }

    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/tp-search-bar.default', WidgetSearchBarHandler);
    });

})(jQuery);