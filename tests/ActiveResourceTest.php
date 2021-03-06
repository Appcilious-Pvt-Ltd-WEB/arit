<?php

use PHPUnit\Framework\TestCase;

class Test extends ActiveResource {
}

class ActiveResourceTest extends TestCase {
	function test_construct () {
		$t = new Test (array ('foo' => 'bar'));

		$this->assertEquals ($t->foo, 'bar');
		$t->foo = 'asdf';
		$this->assertEquals ($t->foo, 'asdf');
		$this->assertEquals ($t->_data, array ('foo' => 'asdf'));
		$this->assertEquals ($t->element_name_plural, 'tests');
	}

	function test_build_xml () {
		$t = new Test;

		// value only		
		$this->assertEquals ($t->_build_xml (0, 'foo'), 'foo');

		// no initial tag
		$this->assertEquals ($t->_build_xml (0, array ('foo' => 'bar')), "<foo>bar</foo>\n");

		// tag and value
		$this->assertEquals ($t->_build_xml ('foo', 'bar'), "<foo>bar</foo>\n");

		// sub-tags
		$this->assertEquals ($t->_build_xml ('foo', array ('bar' => 'asdf')), "<foo><bar>asdf</bar>\n</foo>\n");

		// attributes
		$this->assertEquals ($t->_build_xml ('foo', array ('@bar' => 'asdf')), "<foo bar=\"asdf\"></foo>\n");

		// attributes and content
		$this->assertEquals ($t->_build_xml ('foo', array ('@bar' => 'asdf', 'bar')), "<foo bar=\"asdf\">bar</foo>\n");

		// repeating tags
		$this->assertEquals (
			$t->_build_xml ('foo', array (array ('bar'), array ('bar'))),
			"<foo>bar</foo>\n<foo>bar</foo>\n"
		);

		// repeating tags with attributes
		$this->assertEquals (
			$t->_build_xml ('foo', array (array ('@id' => 'one', 'bar'), array ('@id' => 'two', 'bar'))),
			"<foo id=\"one\">bar</foo>\n<foo id=\"two\">bar</foo>\n"
		);

		// repeating tags with attributes and sub-tags
		$this->assertEquals (
			$t->_build_xml ('foo', array (array ('@id' => 'one', 'bar' => 'asdf'), array ('@id' => 'two', 'bar' => 'qwerty'))),
			"<foo id=\"one\"><bar>asdf</bar>\n</foo>\n<foo id=\"two\"><bar>qwerty</bar>\n</foo>\n"
		);

		// starting from a SimpleXMLElement
		$xml = new SimpleXMLElement ('<foo><bar asdf="qwerty" />what</foo>');
		$this->assertEquals ($t->_build_xml (0, $xml), "<foo><bar asdf=\"qwerty\"/>what</foo>\n");

		// testing objects converted to arrays
		$this->assertEquals (
			$t->_build_xml ('foo', array ((object) array ('bar' => 'asdf'))),
			"<foo><bar>asdf</bar>\n</foo>\n"
		);

		// testing objects converted to arrays
		$this->assertEquals (
			$t->_build_xml ('foo', array ((object) array ('bar' => (object) array ('asdf' => 'qwerty')))),
			"<foo><bar><asdf>qwerty</asdf>\n</bar>\n</foo>\n"
		);
	}

	function test_pleuralize () {
		$t = new Test;
		
		$this->assertEquals ($t->pluralize ('person'), 'people');
		$this->assertEquals ($t->pluralize ('people'), 'people');
		$this->assertEquals ($t->pluralize ('man'), 'men');
		$this->assertEquals ($t->pluralize ('woman'), 'women');
		$this->assertEquals ($t->pluralize ('women'), 'women');
		$this->assertEquals ($t->pluralize ('child'), 'children');
		$this->assertEquals ($t->pluralize ('sheep'), 'sheep');
		$this->assertEquals ($t->pluralize ('octopus'), 'octopi');
		$this->assertEquals ($t->pluralize ('virus'), 'viruses');
		$this->assertEquals ($t->pluralize ('quiz'), 'quizzes');
		$this->assertEquals ($t->pluralize ('axis'), 'axes');
		$this->assertEquals ($t->pluralize ('axe'), 'axes');
		$this->assertEquals ($t->pluralize ('buffalo'), 'buffaloes');
		$this->assertEquals ($t->pluralize ('tomato'), 'tomatoes');
		$this->assertEquals ($t->pluralize ('potato'), 'potatoes');
		$this->assertEquals ($t->pluralize ('ox'), 'oxen');
		$this->assertEquals ($t->pluralize ('mouse'), 'mice');
		$this->assertEquals ($t->pluralize ('matrix'), 'matrices');
		$this->assertEquals ($t->pluralize ('vertex'), 'vertices');
		$this->assertEquals ($t->pluralize ('vortex'), 'vortexes');
		$this->assertEquals ($t->pluralize ('index'), 'indices');
		$this->assertEquals ($t->pluralize ('sandwich'), 'sandwiches');
		$this->assertEquals ($t->pluralize ('mass'), 'masses');
		$this->assertEquals ($t->pluralize ('fax'), 'faxes');
		$this->assertEquals ($t->pluralize ('pin'), 'pins');
		$this->assertEquals ($t->pluralize ('touch'), 'touches');
		$this->assertEquals ($t->pluralize ('sash'), 'sashes');
		$this->assertEquals ($t->pluralize ('bromium'), 'bromia');
		$this->assertEquals ($t->pluralize ('prophecy'), 'prophecies');
		$this->assertEquals ($t->pluralize ('crisis'), 'crises');
		$this->assertEquals ($t->pluralize ('life'), 'lives');
		$this->assertEquals ($t->pluralize ('wife'), 'wives');
		$this->assertEquals ($t->pluralize ('song'), 'songs');
		$this->assertEquals ($t->pluralize ('try'), 'tries');
		$this->assertEquals ($t->pluralize ('tree'), 'trees');
		$this->assertEquals ($t->pluralize ('tries'), 'tries');
		$this->assertEquals ($t->pluralize ('entry'), 'entries');
		$this->assertEquals ($t->pluralize ('entries'), 'entries');
	}

	function test_xml_entities () {
		$t = new Test;
		
		$this->assertEquals ($t->_xml_entities ('asdf'), 'asdf');
		$this->assertEquals ($t->_xml_entities ('<>'), '&lt;&gt;');
		$this->assertEquals ($t->_xml_entities ('"'), '&quot;');
		$this->assertEquals ($t->_xml_entities ('\''), '&apos;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xE4;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xA3;');
		$this->assertEquals ($t->_xml_entities ('???'), '&#x2022;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xC0;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xE0;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xC1;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xE1;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xC2;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xE2;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xC3;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xE3;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xC4;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xE4;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xC5;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xE5;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xC6;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xE6;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xC7;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xE7;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xD0;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xF0;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xC8;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xE8;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xC9;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xE9;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xCA;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xEA;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xCB;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xEB;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xCC;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xEC;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xCD;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xED;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xCE;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xEE;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xCF;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xEF;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xD1;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xF1;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xD2;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xF2;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xD3;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xF3;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xD4;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xF4;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xD5;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xF5;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xD6;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xF6;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xD8;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xF8;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#x152;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#x153;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xDF;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xDE;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xFE;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xD9;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xF9;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xDA;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xFA;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xDB;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xFB;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xDC;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xFC;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xDD;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xFD;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#x178;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#xFF;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#x106;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#x107;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#x10C;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#x10D;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#x110;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#x111;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#x160;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#x161;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#x17D;');
		$this->assertEquals ($t->_xml_entities ('??'), '&#x17E;');
	}
}
