<?php
/**
 * ---------------------------------------------------------------------
 * Formcreator is a plugin which allows creation of custom forms of
 * easy access.
 * ---------------------------------------------------------------------
 * LICENSE
 *
 * This file is part of Formcreator.
 *
 * Formcreator is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Formcreator is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Formcreator. If not, see <http://www.gnu.org/licenses/>.
 * ---------------------------------------------------------------------
 * @copyright Copyright © 2011 - 2021 Teclib'
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @link      https://github.com/pluginsGLPI/formcreator/
 * @link      https://pluginsglpi.github.io/formcreator/
 * @link      http://plugins.glpi-project.org/#/plugin/formcreator
 * ---------------------------------------------------------------------
 */
namespace GlpiPlugin\Formcreator\Field\tests\units;
use GlpiPlugin\Formcreator\Tests\CommonTestCase;
use PluginFormcreatorFormAnswer;
use Glpi\Toolbox\Sanitizer;

class TextareaField extends CommonTestCase {
   public function testGetName() {
      $itemtype = $this->getTestedClassName();
      $output = $itemtype::getName();
      $this->string($output)->isEqualTo('Textarea');
   }

   public function testisPublicFormCompatible() {
      $instance = $this->newTestedInstance($this->getQuestion());
      $output = $instance->isPublicFormCompatible();
      $this->boolean($output)->isTrue();
   }

   public function providerSerializeValue() {
      $question = $this->getQuestion();
      $instance = $this->newTestedInstance($question);
      $key = 'formcreator_field_' . $question->getID();
      $instance->parseAnswerValues([
         $key => '',
      ]);
      yield [
         'instance' => $instance,
         'expected' => '',
      ];

      $question = $this->getQuestion();
      $instance = $this->newTestedInstance($question);
      $key = 'formcreator_field_' . $question->getID();
      $instance->parseAnswerValues([
         $key => "quote \' test",
      ]);
      yield [
         'instance' => $instance,
         'expected' => "quote ' test",
      ];

      //TODO: for some reason this test case fails on github : the fixture file is copied but missing when computing its sha1 checksum
      // Error E_WARNING in /home/runner/work/formcreator/glpi/plugins/formcreator/tests/3-unit/GlpiPlugin/Formcreator/Field/TextareaField.php on line 109, generated by file /home/runner/work/formcreator/glpi/src/Document.php on line 642:
      // sha1_file(/home/runner/work/formcreator/glpi/files/_tmp/5e57f3f5cd1060.93275638image_paste2981048.png): failed to open stream: No such file or directory
      //
      // $instance = $this->newTestedInstance($question);
      // $question = $this->getQuestion();
      // $instance = $this->newTestedInstance($question);
      // $sourceFile = __DIR__;
      // $sourceFile = realpath($sourceFile . '/../../../../fixture/picture.png');
      // $tmpFile = '5e57f3f5cd1060.93275638image_paste2981048.png';
      // $success = copy($sourceFile, GLPI_TMP_DIR . "/$tmpFile");
      // $this->boolean($success)->isTrue();
      // $key = 'formcreator_field_' . $question->getID();
      // $instance->parseAnswerValues([
         // $key               => '&#60;p&#62;&#60;img id="6e48eaef-761764d0-62ed2882556d61.27118334" src="blob:http://localhost:8080/76a3e35c-b083-4127-af53-679d2550834f" data-upload_id="0.9939934546250571"&#62;&#60;/p&#62;',
         // '_' . $key         => [
            // 0 => basename($tmpFile),
         // ],
         // '_prefix_' . $key  => [
            // 0 => '5e57f3f5cd1060.93275638',
         // ],
         // '_tag_' . $key     => [
            // 0 => '6e48eaef-761764d0-62ed2882556d61.27118334',
         // ],
      // ]);
      // yield [
         // 'instance' => $instance,
         // 'expected' => '&#60;p&#62;#6e48eaef-761764d0-62ed2882556d61.27118334#&#60;/p&#62;',
      // ];
   }

   /**
    * @dataProvider providerSerializeValue
    */
   public function testSerializeValue($instance, $expected) {
      $form = $this->getForm();
      $formAnswer = new PluginFormcreatorFormAnswer();
      $formAnswer->add([
         $form::getForeignKeyField() => $form->getID(),
      ]);
      $output = $instance->serializeValue($formAnswer);
      $this->string($output)->isEqualTo($expected);
   }

   public function providerDeserializeValue() {
      $question = $this->getQuestion();
      $key = 'formcreator_field_' . $question->getID();
      yield [
         'question' => $question,
         'input'    => [
            $key    => '',
         ],
         'expected' => '',
      ];

      $question = $this->getQuestion();
      $key = 'formcreator_field_' . $question->getID();
      yield [
         'question' => $question,
         'input'    => [
            $key    => 'foo',
         ],
         'expected' => 'foo',
      ];

      $question = $this->getQuestion();
      $key = 'formcreator_field_' . $question->getID();
      $sourceFile = __DIR__;
      $sourceFile = realpath($sourceFile . '/../../../../fixture/picture.png');
      $tmpFile = '5e57f3f5cd1060.93275600image_paste2981048.png';
      $success = copy($sourceFile, GLPI_TMP_DIR . "/$tmpFile");
      $this->boolean($success)->isTrue();
      yield [
         'question' => $question,
         'input'    => [
            $key    => '&#60;p&#62;&#60;img id=\"6e48eaef-761764d0-62ed2882556d61.27118334\" src=\"blob:http://localhost:8080/76a3e35c-b083-4127-af53-679d2550834f\" data-upload_id=\"0.7577303544485556\"&#62;&#60;/p&#62;',
            "_$key" => [
               0 => basename($tmpFile),
            ],
            "_prefix_$key" => [
               0 => '5e57f3f5cd1060.93275600',
            ],
            "_tag_$key" => [
               0 => '6e48eaef-761764d0-62ed2882556d61.27118334',
            ],
         ],
         'expected' => '<p><img id="6e48eaef-761764d0-62ed2882556d61.27118334" src="blob:http://localhost:8080/76a3e35c-b083-4127-af53-679d2550834f" data-upload_id="0.7577303544485556"></p>',
      ];
   }

   /**
    * @dataProvider providerDeserializeValue
    */
   public function testDeserializeValue($question, $input, $expected) {
      $instance = $this->newTestedInstance($question);
      $key = 'formcreator_field_' . $question->getID();

      $instance->parseAnswerValues($input);
      $instance->deserializeValue($input[$key]);
      $output = $instance->getValueForTargetText('', true);
      $this->string($output)->isEqualTo($expected);
   }

   public function testCanRequire() {
      $instance = $this->newTestedInstance($this->getQuestion());
      $output = $instance->canRequire();
      $this->boolean($output)->isTrue();
   }

   public function testGetDocumentsForTarget() {
      $instance = $this->newTestedInstance($this->getQuestion());
      $this->array($instance->getDocumentsForTarget())->hasSize(0);
   }

   public function providerEquals() {
      return [
         [
            'value'      => '',
            'comparison' => '',
            'expected'   => true,
         ],
         [
            'value'      => 'foo',
            'comparison' => 'bar',
            'expected'   => false,
         ],
         [
            'value'      => '',
            'comparison' => 'bar',
            'expected'   => false,
         ],
         [
            'value'      => 'foo',
            'comparison' => '',
            'expected'   => false,
         ],
         [
            'value'      => 'foo',
            'comparison' => 'foo',
            'expected'   => true,
         ],
      ];
   }

   /**
    * @dataProvider providerEquals
    *
    */
   public function testEquals($value, $comparison, $expected) {
      $question = $this->getQuestion();
      $key = 'formcreator_field_' . $question->getID();
      $instance = $this->newTestedInstance($question);
      $input = [
         $key => $value,
      ];
      $instance->parseAnswerValues($input, true);
      $output =$instance->equals($comparison);
      $this->boolean($output)->isEqualTo($expected);
   }

   /**
    * @dataProvider providerEquals
    *
    */
   public function testNotEquals($value, $comparison, $expected) {
      $question = $this->getQuestion();
      $key = 'formcreator_field_' . $question->getID();
      $instance = $this->newTestedInstance($question);
      $input = [
         $key => $value,
      ];
      $instance->parseAnswerValues($input, true);
      $output =$instance->notEquals($comparison);
      $this->boolean($output)->isEqualTo(!$expected);
   }

   public function providerGreaterThan() {
      return [
         [
            'value'      => '',
            'comparison' => '',
            'expected'   => false,
         ],
         [
            'value'      => 'foo',
            'comparison' => 'foo',
            'expected'   => false,
         ],
         [
            'value'      => 'foo',
            'comparison' => 'foo',
            'expected'   => false,
         ],
         [
            'value'      => 'foo',
            'comparison' => 'bar',
            'expected'   => true,
         ],
         [
            'value'      => 'bar',
            'comparison' => 'foo',
            'expected'   => false,
         ],
      ];
   }

   /**
    * @dataProvider providerGreaterThan
    *
    */
   public function testGreaterThan($value, $comparison, $expected) {
      $question = $this->getQuestion();
      $key = 'formcreator_field_' . $question->getID();
      $instance = $this->newTestedInstance($question);
      $input = [
         $key => $value,
      ];
      $instance->parseAnswerValues($input, true);
      $output = $instance->greaterThan($comparison);
      $this->boolean($output)->isEqualTo($expected);
   }

   public function providerGetValueForApi() {
      return [
         [
            'input'    => 'this is a text',
            'expected' => 'this is a text',
         ],
      ];
   }

   /**
    * @dataProvider providerGetValueForApi
    *
    * @return void
    */
   public function testGetValueForApi($input, $expected) {
      $question = $this->getQuestion([]);

      $instance = $this->newTestedInstance($question);
      $instance->deserializeValue($input);
      $output = $instance->getValueForApi();
      $this->string($output)->isEqualTo($expected);
   }

   public function testGetRenderedHtml() {
      // XSS check
      $formAnswer = new PluginFormcreatorFormAnswer();
      $instance = $this->newTestedInstance($this->getQuestion());
      $instance->setFormAnswer($formAnswer);
      $instance->deserializeValue('"><img src=x onerror="alert(1337)" x=x>');
      $output = $instance->getRenderedHtml('no_domain', false);
      $this->string($output)->isEqualTo('"&gt;<img src="x" alt="image"  loading="lazy">');
      $output = $instance->getRenderedHtml('no_domain', true);
      $this->string($output)->contains('"><img src=x onerror="alert(1337)" x=x>');
   }

   public function providerGetValueForTargetText() {
      $fieldtype = 'textarea';
      yield [
         'question' => $this->getQuestion([
            'fieldtype' => $fieldtype,
         ]),
         'value' => '<p>foo</p><p>bar</p>',
         'expectedValue' => 'foobar',
         'expectedRichValue' => '<p>foo</p><p>bar</p>',
      ];
   }
}
