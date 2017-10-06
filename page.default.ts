config.no_cache = 1

config.doctype = html5
config.contentObjectExceptionHandler = 0
config.metaCharset = utf-8

lib.fluidContent {
	templateName = Default
	templateRootPaths {
		#0 = EXT:fluid_styled_content/Resources/Private/Templates/
		10 = {$test_wrapper.styles.templates.templateRootPath}
	}
	partialRootPaths {
		#0 = EXT:fluid_styled_content/Resources/Private/Partials/
		10 = {$test_wrapper.styles.templates.partialRootPath}
	}
	layoutRootPaths {
		#0 = EXT:fluid_styled_content/Resources/Private/Layouts/
		10 = {$test_wrapper.styles.templates.layoutRootPath}
	}
}

page = PAGE
page.typeNum = 0
page {
	10 = FLUIDTEMPLATE
	10 {
		format = html

		file.stdWrap.cObject = CASE
		file.stdWrap.cObject {
			key.data = levelfield:-1, backend_layout_next_level, slide
			key.override.field = backend_layout
	
			default = TEXT
			default.value = {$test_wrapper.page.fluidtemplate.templateRootPath}Default.html
			default.insertData = 1
			
			pagets__10 = TEXT
			pagets__10.value = {$test_wrapper.page.fluidtemplate.templateRootPath}Default.html
			pagets__10.insertData = 1
			
			pagets__20 = TEXT
			pagets__20.value = {$test_wrapper.page.fluidtemplate.templateRootPath}Home.html
			pagets__20.insertData = 1
			
			
		}
		
		#partialRootPaths {
		#	10 = {$test_wrapper.page.fluidtemplate.partialRootPath}
		#}
		#layoutRootPaths {
		#	10 = {$test_wrapper.page.fluidtemplate.layoutRootPath}
		#}
		#templateRootPaths {
		#	10 = {$test_wrapper.page.fluidtemplate.templateRootPath}
		#}
		partialRootPath = {$test_wrapper.page.fluidtemplate.partialRootPath}
		layoutRootPath = {$test_wrapper.page.fluidtemplate.layoutRootPath}
		
		variables {
			# For home
			pageintro >
			pageintro < styles.content.get
			pageintro.select.where			= colpos = 111
			content_top >
			content_top.select.where 		= colPos = 121
			content_middle >
			content_middle.select.where 	= colPos = 131
			content_bottom >
			content_bottom.select.where 	= colPos = 141
			# For 1col
			content >
			content < styles.content.get
			content.select.where 			= colPos = 11
			# For sidebarleft
			column_left >
			column_left < styles.content.get
			column_left.select.where 		= colPos = 140
			content_right >
			content_right < styles.content.get
			content_right.select.where 		= colPos = 341
			# For sidebarright
			content_left >
			content_left < styles.content.get
			content_left.select.where 		= colPos = 340
			column_right >
			column_right < styles.content.get
			column_right.select.where 		= colPos = 141
			# LINKS
			link-facebook = TEXT
			link-facebook.value 	= {$test_wrapper.page.social.facebook}

			link-pinterest = TEXT
			link-pinterest.value 	= {$test_wrapper.page.social.pinterest}

			link-twitter = TEXT
			link-twitter.value 		= {$test_wrapper.page.social.twitter}

			link-dribble = TEXT
			link-dribble.value 		= {$test_wrapper.page.social.dribble}

			link-google-plus = TEXT
			link-google-plus.value 	= {$test_wrapper.page.social.google-plus}

			link-vk = TEXT
			link-vk.value 			= {$test_wrapper.page.social.vk}

			link-youtube = TEXT
			link-youtube.value 		= {$test_wrapper.page.social.youtube}

			link-rss = TEXT
			link-rss.value 			= {$test_wrapper.page.social.rss}
			
			# INFO
			phone = TEXT
			phone.value			= {$test_wrapper.page.info.phone}
			
			adress = TEXT
			adress.value		= {$test_wrapper.page.info.adress.street} &amp; {$test_wrapper.page.info.adress.number}, {$test_wrapper.page.info.adress.town}, {$test_wrapper.page.info.adress.postcode}
			
			mail = TEXT
			mail.value 			= {$test_wrapper.page.info.mail}
			
			fax = TEXT
			fax.value			= {$test_wrapper.page.info.fax}
			
			copyright			= TEXT
			copyright.value		= {$test_wrapper.page.copyright}
			
			domain_name			= TEXT
			domain_name.value = {$test_wrapper.const.siteName}.com
			
			top-background = TEXT
			top-background.value = {$test_wrapper.page.files.images.top-background}
		}
	}
	includeCSS {
		page-css = {$test_wrapper.page.includePath.css}layout.css
		magnific-popup-css = {$test_wrapper.page.includePath.css}magnific-popup.css
	}
	includeJSFooter {
		jQuery = {$test_wrapper.page.includePath.js}jquery.min.js
		backtotop = {$test_wrapper.page.includePath.js}jquery.backtotop.js
		mobilemenu = {$test_wrapper.page.includePath.js}jquery.mobilemenu.js
		magnific-popup = {$test_wrapper.page.includePath.js}jquery.magnific-popup.js
		main = {$test_wrapper.page.includePath.js}main.js
	}
}
ajaxContent = PAGE
ajaxContent {
    typeNum = 476
    # disable header code
    config {
        disableAllHeaderCode = 1
        additionalHeaders = Content-type: application/html, utf-8

        xhtml_cleaning = 0
        admPanel = 0
        debug = 0
    }
	# bootstrap setup
	10 = USER_INT
	10 {
		userFunc = TYPO3\CMS\Extbase\Core\Bootstrap->run
		pluginName = Pi1
		vendorName = GeorgRinger
		extensionName = News
		controller = News
		view =< plugin.tx_news.view
		switchableControllerActions {
			News {
				1 = list
				2 = ajax
				3 = gallery
			}
		}
		#settings.categoryConjunction = or
	}
}
ajaxPagination = PAGE
ajaxPagination {
    typeNum = 477
    # disable header code
    config {
        disableAllHeaderCode = 1
        additionalHeaders = Content-type: application/html, utf-8

        xhtml_cleaning = 0
        admPanel = 0
        debug = 0
    }
	# bootstrap setup
	10 = USER_INT
	10 {
		userFunc = TYPO3\CMS\Extbase\Core\Bootstrap->run
		pluginName = Pi1
		vendorName = GeorgRinger
		extensionName = News
		controller = Category
		view =< plugin.tx_news.view
		switchableControllerActions {
			News {
				1 = list
			}
		}
		#settings.categoryConjunction = or
	}
}
lib.main-nav = HMENU
lib.main-nav {
	1 = TMENU
	1 {
		expAll = 1
		NO = 1
		NO.wrapItemAndSub = <li>|</li>

		CUR < .NO
		CUR.wrapItemAndSub = <li class="active">|</li>
		
		IFSUB < .NO
		IFSUB {
			doNotLinkIt = 1
			subst_elementUid = 1
		}
		
		IFSUB.stdWrap.cObject = CASE
		IFSUB.stdWrap.cObject {
			key.field = doktype
			default = TEXT
			default {
				field = title
			}
			# standard page type
			1 = COA
			1 {
				#10 = TEXT
				#10.data = getIndpEnv:TYPO3_REQUEST_URL
				#10.wrap = 
				20 = TEXT
				20.field = title
				20.wrap = <a href="index.php?id={elementUid}">|</a>
			}
			# shortcut page type
			4 = TEXT
			4 {
				field = title
				wrap = <a href="javascripy:void(0)" class="drop">|</a>
			}
		}
		CURIFSUB < .IFSUB
		CURIFSUB {
			wrapItemAndSub = <li class="active">|</li>
		}
		
		wrap = <ul class="clear">|</ul>
	}
	2 < .1
	2.wrap = <ul>|</ul>
	3 < .2
}
lib.side-nav < lib.main-nav
lib.side-nav {
	entryLevel = 1
	1.CUR < 1.NO
	1.IFSUB < 1.NO
	1.CURIFSUB < 1.NO
	1.wrap = <nav class="sdb_holder"><ul>|</ul></nav>
}

lib.temp = COA
lib.temp < styles.content.get
lib.temp.select.where = tx_gridelements_columns = 11

lib.content = COA
lib.content {
	
	10 < styles.content.get
	10.select.where {
		colPos = 11
		tx_gridelements_columns = 11
	}
}

lib.breadcrumb = COA
lib.breadcrumb {
	10 = HMENU
	10 {
		special = rootline
		special.range = 0|-1
		# "not in menu pages" should show up in the breadcrumbs menu
		#includeNotInMenu = 1
		1 = TMENU
		1 {
			NO = 1
			NO.wrapItemAndSub = <li>|</li>
			NO.linkWrap = <a href="javascript:void(0)">|</a>
			NO.doNotLinkIt = 1
			
			ACT < .NO
			ACT = 1
			ACT.wrapItemAndSub = <li class="active">|</li>
			
			wrap = <ul>|</ul>
			
			target = _self
		}
	}
}
lib.header_logo = TEXT
lib.header_logo.value = POBABINI
lib.header_logo.wrap = <a href="index.php">|</a>

lib.content_footer = COA
lib.content_footer {
    10 = CONTENT
    10 {
        table = tt_content
        select.where = colPos = 11
        select.pidInList = {$test_wrapper.const.root.footer}
    }
}
lib.newsletter-sign-up-form = COA
lib.newsletter-sign-up-form {
    10 = CONTENT
    10 {
        table = tt_content
        select.where = colPos = 11
        select.pidInList = {$test_wrapper.const.root.newsletter-sign-up-form}
    }
}