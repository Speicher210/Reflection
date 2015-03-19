# The Reflection Component

## Overview

The Reflection Component allows you to introspect and reverse engineer functions, classes, extension and traits.
You can also retrieve the documentation from classes and functions.
Included in this component, you will also find a toolbox for annotations reflection.

### Usage

Reflection component mainly reproduce the behavior of PHP Reflection. You can find all details and examples on [PHP documentation website](http://www.php.net/manual/en/book.reflection.php).

We'll describe briefly the few additions of Wingu OctopusCore. Let start with reflection class :

#### ReflectionClass

The methods getOwnInterfaces, getOwnMethods, hasOwnMethod and getOwnProperties returns elements declared by the inspected class and not the ones defined by parent classes.
Here are some examples :

```php
use Wingu\OctopusCore\Reflection\ReflectionClass;

interface MyInterface1 {

    public function myMethod1();
}

class MyClassA implements MyInterface1 {

    public $myAttributeA;

    public function myMethod1() {
    }
}

interface MyInterface2 {

    public function myMethod2();
}

class MyClassB extends MyClassA implements MyInterface2 {

    public $myAttributeB;

    public function myMethod2() {
    }
}

$class = new ReflectionClass('MyClassB');

$hasOwnMethod = $class->hasOwnMethod('method1');
var_dump($hasOwnMethod);

$ownMethods = $class->getOwnMethods();
var_dump($ownMethods);

$ownInterfaces = $class->getOwnInterfaces();
var_dump($ownMethods);

$ownProperties = $class->getOwnProperties();
var_dump($ownProperties);
```

The above example will output something similar to::
```php
   bool(false)
   array(1) {
     [0] =>
     class Wingu\OctopusCore\Reflection\ReflectionMethod#5 (3) {
       public $name =>
       string(9) "myMethod2"
       public $class =>
       string(8) "MyClassB"
       protected $reflectionDocComment =>
       NULL
     }
   }
   array(1) {
     [0] =>
     class Wingu\OctopusCore\Reflection\ReflectionMethod#5 (3) {
       public $name =>
       string(9) "myMethod2"
       public $class =>
       string(8) "MyClassB"
       protected $reflectionDocComment =>
       NULL
     }
   }
   array(1) {
     [0] =>
     class Wingu\OctopusCore\Reflection\ReflectionProperty#4 (3) {
       public $name =>
       string(12) "myAttributeB"
       public $class =>
       string(8) "MyClassB"
       protected $reflectionDocComment =>
       NULL
     }
   }
```

Other useful methods are:
```php
	$class->getBody(); // Get the body of the class.
	$class->getUses(); // Returns an array of Wingu\OctopusCore\Reflection\ReflectionClassUse
	$class->hasOwnMethod('method_name'); // Check if a method exists and is defined in this class and not a parent.
```

#### ReflectionClassUse

This is a reflection upon the use statements in a class (traits).

```php
	class MyClass {

    	use MyTrait {
        	MyTrait::trait2Function2 as tf2;
        	MyTrait::publicFunc as protected;
        }

        use MyTrait2;
    }

	$reflection = new ReflectionClassUse('MyClass', 'MyTrait');

	$reflection->getName(); // MyTrait
	$reflection->getConflictResolutions();  // ['MyTrait::trait2Function2 as tf2', 'MyTrait::publicFunc as protected']
```
#### ReflectionConstant

This object encapsulates the name-value as described above:

```php
   use Wingu\OctopusCore\Reflection\ReflectionConstant;

   class MyClass {
       const CONSTANT1 = 'MY_CONSTANT_VALUE1';
   }

   $constant = new ReflectionConstant('MyClass', 'CONSTANT1');
   var_dump($constant);
```

The above example will output something similar to::
```php
   class Wingu\OctopusCore\Reflection\ReflectionConstant#2 (4) {
     private $name =>
     string(9) "CONSTANT1"
     private $value =>
     string(18) "MY_CONSTANT_VALUE1"
     private $declaringClass =>
     class Wingu\OctopusCore\Reflection\ReflectionClass#3 (2) {
       public $name =>
       string(7) "MyClass"
       protected $reflectionDocComment =>
       NULL
     }
     protected $reflectionDocComment =>
     NULL
   }
```


#### ReflectionDocComment

The ReflectionDocComment class allows you to extract and do some light-parsing on the structure of the comment:

```php
   use Wingu\OctopusCore\Reflection\ReflectionDocComment;

   $comment = new ReflectionDocComment('/**
       * This is a short description of MyClass
       * This is a long description of MyClass
       *
       * @param paramtest
       * @category category1
       *
       */');

   var_dump($comment);
```

The above example will output something similar to::
```php
   class Wingu\OctopusCore\Reflection\ReflectionDocComment#2 (3) {
     private $originalDocBlock =>
     string(160) "/**\n    * This is a short description of MyClass\n    * This is a long description of MyClass\n    *\n    * @param paramtest\n    * @category category1\n    *\n    */"
     protected $shortDescription =>
     string(38) "This is a short description of MyClass"
     protected $longDescription =>
     string(37) "This is a long description of MyClass"
   }
```

You can also get the annotations collections (getAnnotationsCollection). The annotations functionalities are described in the Annotation section.

You can get the ReflectionDocComment from element with the behavior which is supplied by ReflectionDocCommentTrait.
That means you can call the getReflectionDocComment method on objects of the following class: ReflectionClass, ReflectionFunction, ReflectionConstant, ReflectionMethod, ReflectionProperty.

#### AnnotationsCollection

From a ReflectionDocComment, you can extract a collection of Annotations through the getAnnotationsCollection which return an AnnotationsCollection object.
To extract the Tags which are the object encapsulation of the annotations, you will need to use the getAnnotations method.

Here is an example of how to use it :

```php
   use Wingu\OctopusCore\Reflection\ReflectionClass;

   /**
    * This is my class
    *
    * @author author1
    * @version 1.2.3
    */
   class MyClass{

   }

   $class = new ReflectionClass('MyClass');
   $comment = $class->getReflectionDocComment();
   $annotationsCollection = $comment->getAnnotationsCollection();
   var_dump($annotationsCollection->getAnnotations());
```

The above example will output something similar to::
```php
   array(2) {
     [0] =>
     class Wingu\OctopusCore\Reflection\Annotation\Tags\BaseTag#5 (3) {
       protected $definition =>
       class Wingu\OctopusCore\Reflection\Annotation\AnnotationDefinition#6 (2) {
         protected $tag =>
         string(6) "author"
         protected $description =>
         string(7) "author1"
       }
       protected $tagName =>
       string(6) "author"
       protected $description =>
       string(7) "author1"
     }
     [1] =>
     class Wingu\OctopusCore\Reflection\Annotation\Tags\BaseTag#8 (3) {
       protected $definition =>
       class Wingu\OctopusCore\Reflection\Annotation\AnnotationDefinition#7 (2) {
         protected $tag =>
         string(7) "version"
         protected $description =>
         string(5) "1.2.3"
       }
       protected $tagName =>
       string(7) "version"
       protected $description =>
       string(5) "1.2.3"
     }
   }
```

#### TagMapper

The TagMapper is used when you try to get the annotations (Tags) from a AnnotationsCollection object.

To build the Tag object corresponding to the annotation, the workflow is as follows :

1. Use the TagMapper set by the setTagMapper method
2. Use one of the Standard tag classes of the package Wingu\OctopusCore\Reflection\Annotation\Tags
3. Use the BaseTag as default

Here's an example of how you can use a TagMapper :

```php
   use Wingu\OctopusCore\Reflection\Annotation\Tags\TagInterface;
   use Wingu\OctopusCore\Reflection\ReflectionClass;
   use Wingu\OctopusCore\Reflection\ReflectionMethod;
   use Wingu\OctopusCore\Reflection\Annotation\TagMapper;

   class MyClass {

       /**
        * @myCustomTag Some description.
        */
       public function method1($myparam1) {
           return 'return';
       }
   }

   class MyCustomTag implements TagInterface {

       public function getTagName() {
           return 'myCustomTag';
       }

       public function getDescription() {
           // Parse and extract the description.
           return 'Some description.';
       }
   }

   $class = new ReflectionMethod('MyClass', 'method1');
   $comment = $class->getReflectionDocComment();
   $annotationsCollection = $comment->getAnnotationsCollection();

   $tagMapper = new TagMapper();
   $tagMapper->mapTag('myCustomTag', 'MyCustomTag');

   $annotationsCollection->setTagMapper($tagMapper);
   var_dump($annotationsCollection->getAnnotations());
```

The output will be ::
```php
   array(1) {
     [0] =>
     class MyCustomTag#7 (0) {
     }
   }
```

The custom Tag classes must implements the TagInterface.
Refer to the ParamTag for a more advanced example.