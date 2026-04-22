<?php

namespace TCG\Voyager\Tests\Unit\Actions;

use TCG\Voyager\Actions\AbstractAction;
use TCG\Voyager\Actions\DeleteAction;
use TCG\Voyager\Actions\EditAction;
use TCG\Voyager\Actions\RestoreAction;
use TCG\Voyager\Actions\ViewAction;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Tests\TestCase;

class AbstractActionTest extends TestCase
{
    /**
     * The users DataType instance.
     *
     * @var \TCG\Voyager\Models\DataType
     */
    protected $userDataType;

    /**
     * A dummy user instance.
     *
     * @var \TCG\Voyager\Models\User
     */
    protected $user;

    private function makeTestAction(?callable $dataTypeResolver = null, array $attributes = [], $defaultRoute = true): AbstractAction
    {
        return new class($this->userDataType, $this->user, $dataTypeResolver, $attributes, $defaultRoute) extends AbstractAction
        {
            private $dataTypeResolver;
            private $attributes;
            private $defaultRoute;

            public function __construct($dataType, $data, ?callable $dataTypeResolver, array $attributes, $defaultRoute)
            {
                parent::__construct($dataType, $data);

                $this->dataTypeResolver = $dataTypeResolver;
                $this->attributes = $attributes;
                $this->defaultRoute = $defaultRoute;
            }

            public function getTitle()
            {
                return 'Test Action';
            }

            public function getIcon()
            {
                return 'voyager-eye';
            }

            public function getDataType()
            {
                return $this->dataTypeResolver ? ($this->dataTypeResolver)() : null;
            }

            public function getAttributes()
            {
                return $this->attributes;
            }

            public function getDefaultRoute()
            {
                return $this->defaultRoute;
            }
        };
    }

    public function setUp(): void
    {
        parent::setUp();

        $role = \TCG\Voyager\Models\Role::create(['name' => 'test_role', 'display_name' => 'Test Role']);
        $this->userDataType = Voyager::model('DataType')->where('name', 'users')->first();
        $this->user = \TCG\Voyager\Models\User::factory()->create();
    }

    /**
     * This test checks that `getRoute` method calls the `getDefaultRoute`
     * method if the given key is empty.
     */
    public function testGetRouteWithEmptyKey()
    {
        $stub = $this->makeTestAction(defaultRoute: true);

        $this->assertTrue($stub->getRoute($this->userDataType->name));
    }

    /**
     * This test checks that `getAttributes` method will give us the expected
     * output.
     */
    public function testConvertAttributesToHtml()
    {
        $stub = $this->makeTestAction(attributes: [
            'class'   => 'class1 class2',
            'data-id' => 5,
            'id'      => 'delete-5',
        ]);

        $this->assertEquals('class="class1 class2" data-id="5" id="delete-5"', $stub->convertAttributesToHtml());
    }

    /**
     * This test checks that `shouldActionDisplayOnDataType` returns true
     * when no data type filter is defined.
     */
    public function testShouldActionDisplayOnDataTypeReturnsTrueWhenNoDataTypeFilterIsDefined()
    {
        $stub = $this->makeTestAction(dataTypeResolver: fn () => null);

        $this->assertTrue($stub->shouldActionDisplayOnDataType());
    }

    /**
     * This test checks that `shouldActionDisplayOnDataType` returns true
     * when the action is filtered by the current DataType object.
     */
    public function testShouldActionDisplayOnDataTypeReturnsTrueWithMatchingDataTypeObject()
    {
        $stub = $this->makeTestAction(dataTypeResolver: fn () => $this->userDataType);

        $this->assertTrue($stub->shouldActionDisplayOnDataType());
    }

    /**
     * This test checks that the built-in actions are shown for their current
     * data type unless they explicitly override the display rule.
     */
    public function testBuiltInActionsAreDisplayedForCurrentDataTypeByDefault()
    {
        foreach ([DeleteAction::class, EditAction::class, RestoreAction::class, ViewAction::class] as $actionClass) {
            $action = new $actionClass($this->userDataType, $this->user);

            $this->assertTrue(
                $action->shouldActionDisplayOnDataType(),
                sprintf('%s should be visible for the current data type.', $actionClass)
            );
        }
    }

    /**
     * This test checks that `shouldActionDisplayOnDataType` method returns true
     * if the action should only be displayed for a specific data type.
     */
    public function testTrueIsReturnedIfDataTypeMatchesTheOneWhereTheActionWasCreatedFor()
    {
        $stub = $this->makeTestAction(dataTypeResolver: fn () => $this->userDataType->name);

        $this->assertTrue($stub->shouldActionDisplayOnDataType());
    }

    /**
     * This test checks that `shouldActionDisplayOnDataType` method returns false
     * if the action should only be displayed for a specific data type.
     */
    public function testFalseIsReturnedIfDataTypeDoesNotMatchesTheOneWhereTheActionWasCreatedFor()
    {
        $stub = $this->makeTestAction(dataTypeResolver: fn () => 'not users');

        $this->assertFalse($stub->shouldActionDisplayOnDataType());
    }

    /**
     * This test checks that `shouldActionDisplayOnDataType` returns false
     * when the action is filtered by a different DataType object.
     */
    public function testShouldActionDisplayOnDataTypeReturnsFalseWithDifferentDataTypeObject()
    {
        $differentDataType = clone $this->userDataType;
        $differentDataType->name = 'posts';

        $stub = $this->makeTestAction(dataTypeResolver: fn () => $differentDataType);

        $this->assertFalse($stub->shouldActionDisplayOnDataType());
    }
}
