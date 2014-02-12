<?php
#
# Markdown Extra  -  A text-to-HTML conversion tool for web writers
#
# PHP Markdown Extra
# Copyright (c) 2004-2013 Michel Fortin  
# <http://michelf.com/projects/php-markdown/>
#
# Original Markdown
# Copyright (c) 2004-2006 John Gruber  
# <http://daringfireball.net/projects/markdown/>
#
namespace Michelf;


# Just force Michelf/Markdown.php to load. This is needed to load
# the temporary implementation class. See below for details.
\Michelf\Markdown::MARKDOWNLIB_VERSION;

#
# Markdown Extra Parser Class
#
# story: Currently the implementation resides in the temporary class
# \Michelf\MarkdownExtra_TmpImpl (in the same file as \Michelf\Markdown).
# This makes it easier to propagate the changes between the three different
# packaging styles of PHP Markdown. Once this issue is resolved, the
# _MarkdownExtra_TmpImpl will disappear and this one will contain the code.
#

class MarkdownExtra extends \Michelf\_MarkdownExtra_TmpImpl {

	### Parser Implementation ###

	# Temporarily, the implemenation is in the _MarkdownExtra_TmpImpl class.
	# See story above.


	public function __construct() {

		$this->document_gamut += array(
			"doScroll"           => 30,
			);

		parent::__construct();
	}
	
	protected function doScroll($text) {
		$less_than_tab = $this->tab_width;
		
		$text = preg_replace_callback('{
				(?:\n|\A)
				# 1: Opening marker
				(
					(?:--@@--)
				)
				[ ]*
				(?:
					\.?([-_:a-zA-Z0-9]+) # 2: standalone class name
				|
					'.$this->id_class_attr_catch_re.' # 3: Extra attributes
				)?
				(?:
					\{(.*)\} # 3: image
				|
					[ ]* \n # Whitespace and newline following marker.
				)

				# 4: Content
				(
					(?>
						(?!\1 [ ]* \n)	# Not a closing marker.
						.*\n+
					)+
				)
				
				# Closing marker.
				\1 [ ]* (?= \n )
			}xm',
			array(&$this, '_doScroll_callback'), $text);


		return $text;
	}

	protected function _doScroll_callback($matches) {
		$classname =& $matches[2];
		$attrs     =& $matches[3];
		$imageblock = $matches[4];
		$codeblock = $matches[5];
		
		$codeblock = htmlspecialchars($codeblock, ENT_NOQUOTES);

		if ($classname != "") {
			if ($classname{0} == '.')
				$classname = substr($classname, 1);
			$attr_str = ' class="'.$this->code_class_prefix.$classname.'"';
		} else {
			$attr_str = $attrs;
		}

		if(empty($imageblock)) {
			$codeblock  = "<div class=\"scroll-block\"><p$attr_str>$codeblock</p></div>";
		} else {
			$codeblock  = "<div class=\"scroll-block have-background-image\" style=\"background-image:url($imageblock);\"><p$attr_str>$codeblock</p></div>";
		}
		return "\n\n".$this->hashBlock($codeblock)."\n\n";
	}
}


?>