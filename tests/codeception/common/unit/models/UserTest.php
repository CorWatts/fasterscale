<?php

namespace tests\codeception\common\unit\models;

use Yii;
use tests\codeception\common\unit\TestCase;
use Codeception\Specify;
use common\models\User;

date_default_timezone_set('UTC');

/**
 * Time test
 */

class UserTest extends TestCase {
  use Specify;

	public $questionData = 'YToxODp7aTowO086MjI6ImNvbW1vblxtb2RlbHNcUXVlc3Rpb24iOjk6e3M6MTM6InF1ZXN0aW9uX3RleHQiO3M6NDU6IkhvdyBkb2VzIGl0IGFmZmVjdCBtZT8gSG93IGRvIEkgYWN0IGFuZCBmZWVsPyI7czozNjoiAHlpaVxkYlxCYXNlQWN0aXZlUmVjb3JkAF9hdHRyaWJ1dGVzIjthOjc6e3M6MjoiaWQiO2k6NjQxO3M6NzoidXNlcl9pZCI7aToyO3M6OToib3B0aW9uX2lkIjtpOjEzO3M6MTQ6InVzZXJfb3B0aW9uX2lkIjtpOjgyMTtzOjg6InF1ZXN0aW9uIjtpOjE7czo2OiJhbnN3ZXIiO3M6NToiYWxzZ24iO3M6NDoiZGF0ZSI7czoxOToiMjAxNi0wOS0xMCAxOToyNzo0MyI7fXM6Mzk6IgB5aWlcZGJcQmFzZUFjdGl2ZVJlY29yZABfb2xkQXR0cmlidXRlcyI7YTo3OntzOjI6ImlkIjtpOjY0MTtzOjc6InVzZXJfaWQiO2k6MjtzOjk6Im9wdGlvbl9pZCI7aToxMztzOjE0OiJ1c2VyX29wdGlvbl9pZCI7aTo4MjE7czo4OiJxdWVzdGlvbiI7aToxO3M6NjoiYW5zd2VyIjtzOjU6ImFsc2duIjtzOjQ6ImRhdGUiO3M6MTk6IjIwMTYtMDktMTAgMTk6Mjc6NDMiO31zOjMzOiIAeWlpXGRiXEJhc2VBY3RpdmVSZWNvcmQAX3JlbGF0ZWQiO2E6MTp7czo2OiJvcHRpb24iO086MjA6ImNvbW1vblxtb2RlbHNcT3B0aW9uIjo4OntzOjM2OiIAeWlpXGRiXEJhc2VBY3RpdmVSZWNvcmQAX2F0dHJpYnV0ZXMiO2E6Mzp7czoyOiJpZCI7aToxMztzOjQ6Im5hbWUiO3M6NDY6Imxlc3MgdGltZS9lbmVyZ3kgZm9yIEdvZCwgbWVldGluZ3MsIGFuZCBjaHVyY2giO3M6MTE6ImNhdGVnb3J5X2lkIjtpOjI7fXM6Mzk6IgB5aWlcZGJcQmFzZUFjdGl2ZVJlY29yZABfb2xkQXR0cmlidXRlcyI7YTozOntzOjI6ImlkIjtpOjEzO3M6NDoibmFtZSI7czo0NjoibGVzcyB0aW1lL2VuZXJneSBmb3IgR29kLCBtZWV0aW5ncywgYW5kIGNodXJjaCI7czoxMToiY2F0ZWdvcnlfaWQiO2k6Mjt9czozMzoiAHlpaVxkYlxCYXNlQWN0aXZlUmVjb3JkAF9yZWxhdGVkIjthOjA6e31zOjIzOiIAeWlpXGJhc2VcTW9kZWwAX2Vycm9ycyI7TjtzOjI3OiIAeWlpXGJhc2VcTW9kZWwAX3ZhbGlkYXRvcnMiO047czoyNToiAHlpaVxiYXNlXE1vZGVsAF9zY2VuYXJpbyI7czo3OiJkZWZhdWx0IjtzOjI3OiIAeWlpXGJhc2VcQ29tcG9uZW50AF9ldmVudHMiO2E6MDp7fXM6MzA6IgB5aWlcYmFzZVxDb21wb25lbnQAX2JlaGF2aW9ycyI7YTowOnt9fX1zOjIzOiIAeWlpXGJhc2VcTW9kZWwAX2Vycm9ycyI7TjtzOjI3OiIAeWlpXGJhc2VcTW9kZWwAX3ZhbGlkYXRvcnMiO047czoyNToiAHlpaVxiYXNlXE1vZGVsAF9zY2VuYXJpbyI7czo3OiJkZWZhdWx0IjtzOjI3OiIAeWlpXGJhc2VcQ29tcG9uZW50AF9ldmVudHMiO2E6MDp7fXM6MzA6IgB5aWlcYmFzZVxDb21wb25lbnQAX2JlaGF2aW9ycyI7YTowOnt9fWk6MTtPOjIyOiJjb21tb25cbW9kZWxzXFF1ZXN0aW9uIjo5OntzOjEzOiJxdWVzdGlvbl90ZXh0IjtzOjUxOiJIb3cgZG9lcyBpdCBhZmZlY3QgdGhlIGltcG9ydGFudCBwZW9wbGUgaW4gbXkgbGlmZT8iO3M6MzY6IgB5aWlcZGJcQmFzZUFjdGl2ZVJlY29yZABfYXR0cmlidXRlcyI7YTo3OntzOjI6ImlkIjtpOjY0MjtzOjc6InVzZXJfaWQiO2k6MjtzOjk6Im9wdGlvbl9pZCI7aToxMztzOjE0OiJ1c2VyX29wdGlvbl9pZCI7aTo4MjE7czo4OiJxdWVzdGlvbiI7aToyO3M6NjoiYW5zd2VyIjtzOjU6ImxvaXVuIjtzOjQ6ImRhdGUiO3M6MTk6IjIwMTYtMDktMTAgMTk6Mjc6NDMiO31zOjM5OiIAeWlpXGRiXEJhc2VBY3RpdmVSZWNvcmQAX29sZEF0dHJpYnV0ZXMiO2E6Nzp7czoyOiJpZCI7aTo2NDI7czo3OiJ1c2VyX2lkIjtpOjI7czo5OiJvcHRpb25faWQiO2k6MTM7czoxNDoidXNlcl9vcHRpb25faWQiO2k6ODIxO3M6ODoicXVlc3Rpb24iO2k6MjtzOjY6ImFuc3dlciI7czo1OiJsb2l1biI7czo0OiJkYXRlIjtzOjE5OiIyMDE2LTA5LTEwIDE5OjI3OjQzIjt9czozMzoiAHlpaVxkYlxCYXNlQWN0aXZlUmVjb3JkAF9yZWxhdGVkIjthOjE6e3M6Njoib3B0aW9uIjtyOjIxO31zOjIzOiIAeWlpXGJhc2VcTW9kZWwAX2Vycm9ycyI7TjtzOjI3OiIAeWlpXGJhc2VcTW9kZWwAX3ZhbGlkYXRvcnMiO047czoyNToiAHlpaVxiYXNlXE1vZGVsAF9zY2VuYXJpbyI7czo3OiJkZWZhdWx0IjtzOjI3OiIAeWlpXGJhc2VcQ29tcG9uZW50AF9ldmVudHMiO2E6MDp7fXM6MzA6IgB5aWlcYmFzZVxDb21wb25lbnQAX2JlaGF2aW9ycyI7YTowOnt9fWk6MjtPOjIyOiJjb21tb25cbW9kZWxzXFF1ZXN0aW9uIjo5OntzOjEzOiJxdWVzdGlvbl90ZXh0IjtzOjQ1OiJXaHkgZG8gSSBkbyB0aGlzPyBXaGF0IGlzIHRoZSBiZW5lZml0IGZvciBtZT8iO3M6MzY6IgB5aWlcZGJcQmFzZUFjdGl2ZVJlY29yZABfYXR0cmlidXRlcyI7YTo3OntzOjI6ImlkIjtpOjY0MztzOjc6InVzZXJfaWQiO2k6MjtzOjk6Im9wdGlvbl9pZCI7aToxMztzOjE0OiJ1c2VyX29wdGlvbl9pZCI7aTo4MjE7czo4OiJxdWVzdGlvbiI7aTozO3M6NjoiYW5zd2VyIjtzOjQ6ImxpdW4iO3M6NDoiZGF0ZSI7czoxOToiMjAxNi0wOS0xMCAxOToyNzo0MyI7fXM6Mzk6IgB5aWlcZGJcQmFzZUFjdGl2ZVJlY29yZABfb2xkQXR0cmlidXRlcyI7YTo3OntzOjI6ImlkIjtpOjY0MztzOjc6InVzZXJfaWQiO2k6MjtzOjk6Im9wdGlvbl9pZCI7aToxMztzOjE0OiJ1c2VyX29wdGlvbl9pZCI7aTo4MjE7czo4OiJxdWVzdGlvbiI7aTozO3M6NjoiYW5zd2VyIjtzOjQ6ImxpdW4iO3M6NDoiZGF0ZSI7czoxOToiMjAxNi0wOS0xMCAxOToyNzo0MyI7fXM6MzM6IgB5aWlcZGJcQmFzZUFjdGl2ZVJlY29yZABfcmVsYXRlZCI7YToxOntzOjY6Im9wdGlvbiI7cjoyMTt9czoyMzoiAHlpaVxiYXNlXE1vZGVsAF9lcnJvcnMiO047czoyNzoiAHlpaVxiYXNlXE1vZGVsAF92YWxpZGF0b3JzIjtOO3M6MjU6IgB5aWlcYmFzZVxNb2RlbABfc2NlbmFyaW8iO3M6NzoiZGVmYXVsdCI7czoyNzoiAHlpaVxiYXNlXENvbXBvbmVudABfZXZlbnRzIjthOjA6e31zOjMwOiIAeWlpXGJhc2VcQ29tcG9uZW50AF9iZWhhdmlvcnMiO2E6MDp7fX1pOjM7TzoyMjoiY29tbW9uXG1vZGVsc1xRdWVzdGlvbiI6OTp7czoxMzoicXVlc3Rpb25fdGV4dCI7czo0NToiSG93IGRvZXMgaXQgYWZmZWN0IG1lPyBIb3cgZG8gSSBhY3QgYW5kIGZlZWw/IjtzOjM2OiIAeWlpXGRiXEJhc2VBY3RpdmVSZWNvcmQAX2F0dHJpYnV0ZXMiO2E6Nzp7czoyOiJpZCI7aTo2NDQ7czo3OiJ1c2VyX2lkIjtpOjI7czo5OiJvcHRpb25faWQiO2k6Mjk7czoxNDoidXNlcl9vcHRpb25faWQiO2k6ODIzO3M6ODoicXVlc3Rpb24iO2k6MTtzOjY6ImFuc3dlciI7czo0OiJsam5iIjtzOjQ6ImRhdGUiO3M6MTk6IjIwMTYtMDktMTAgMTk6Mjc6NDMiO31zOjM5OiIAeWlpXGRiXEJhc2VBY3RpdmVSZWNvcmQAX29sZEF0dHJpYnV0ZXMiO2E6Nzp7czoyOiJpZCI7aTo2NDQ7czo3OiJ1c2VyX2lkIjtpOjI7czo5OiJvcHRpb25faWQiO2k6Mjk7czoxNDoidXNlcl9vcHRpb25faWQiO2k6ODIzO3M6ODoicXVlc3Rpb24iO2k6MTtzOjY6ImFuc3dlciI7czo0OiJsam5iIjtzOjQ6ImRhdGUiO3M6MTk6IjIwMTYtMDktMTAgMTk6Mjc6NDMiO31zOjMzOiIAeWlpXGRiXEJhc2VBY3RpdmVSZWNvcmQAX3JlbGF0ZWQiO2E6MTp7czo2OiJvcHRpb24iO086MjA6ImNvbW1vblxtb2RlbHNcT3B0aW9uIjo4OntzOjM2OiIAeWlpXGRiXEJhc2VBY3RpdmVSZWNvcmQAX2F0dHJpYnV0ZXMiO2E6Mzp7czoyOiJpZCI7aToyOTtzOjQ6Im5hbWUiO3M6MTU6InVzaW5nIHByb2Zhbml0eSI7czoxMToiY2F0ZWdvcnlfaWQiO2k6Mzt9czozOToiAHlpaVxkYlxCYXNlQWN0aXZlUmVjb3JkAF9vbGRBdHRyaWJ1dGVzIjthOjM6e3M6MjoiaWQiO2k6Mjk7czo0OiJuYW1lIjtzOjE1OiJ1c2luZyBwcm9mYW5pdHkiO3M6MTE6ImNhdGVnb3J5X2lkIjtpOjM7fXM6MzM6IgB5aWlcZGJcQmFzZUFjdGl2ZVJlY29yZABfcmVsYXRlZCI7YTowOnt9czoyMzoiAHlpaVxiYXNlXE1vZGVsAF9lcnJvcnMiO047czoyNzoiAHlpaVxiYXNlXE1vZGVsAF92YWxpZGF0b3JzIjtOO3M6MjU6IgB5aWlcYmFzZVxNb2RlbABfc2NlbmFyaW8iO3M6NzoiZGVmYXVsdCI7czoyNzoiAHlpaVxiYXNlXENvbXBvbmVudABfZXZlbnRzIjthOjA6e31zOjMwOiIAeWlpXGJhc2VcQ29tcG9uZW50AF9iZWhhdmlvcnMiO2E6MDp7fX19czoyMzoiAHlpaVxiYXNlXE1vZGVsAF9lcnJvcnMiO047czoyNzoiAHlpaVxiYXNlXE1vZGVsAF92YWxpZGF0b3JzIjtOO3M6MjU6IgB5aWlcYmFzZVxNb2RlbABfc2NlbmFyaW8iO3M6NzoiZGVmYXVsdCI7czoyNzoiAHlpaVxiYXNlXENvbXBvbmVudABfZXZlbnRzIjthOjA6e31zOjMwOiIAeWlpXGJhc2VcQ29tcG9uZW50AF9iZWhhdmlvcnMiO2E6MDp7fX1pOjQ7TzoyMjoiY29tbW9uXG1vZGVsc1xRdWVzdGlvbiI6OTp7czoxMzoicXVlc3Rpb25fdGV4dCI7czo1MToiSG93IGRvZXMgaXQgYWZmZWN0IHRoZSBpbXBvcnRhbnQgcGVvcGxlIGluIG15IGxpZmU/IjtzOjM2OiIAeWlpXGRiXEJhc2VBY3RpdmVSZWNvcmQAX2F0dHJpYnV0ZXMiO2E6Nzp7czoyOiJpZCI7aTo2NDU7czo3OiJ1c2VyX2lkIjtpOjI7czo5OiJvcHRpb25faWQiO2k6Mjk7czoxNDoidXNlcl9vcHRpb25faWQiO2k6ODIzO3M6ODoicXVlc3Rpb24iO2k6MjtzOjY6ImFuc3dlciI7czo1OiJsaXVuYiI7czo0OiJkYXRlIjtzOjE5OiIyMDE2LTA5LTEwIDE5OjI3OjQzIjt9czozOToiAHlpaVxkYlxCYXNlQWN0aXZlUmVjb3JkAF9vbGRBdHRyaWJ1dGVzIjthOjc6e3M6MjoiaWQiO2k6NjQ1O3M6NzoidXNlcl9pZCI7aToyO3M6OToib3B0aW9uX2lkIjtpOjI5O3M6MTQ6InVzZXJfb3B0aW9uX2lkIjtpOjgyMztzOjg6InF1ZXN0aW9uIjtpOjI7czo2OiJhbnN3ZXIiO3M6NToibGl1bmIiO3M6NDoiZGF0ZSI7czoxOToiMjAxNi0wOS0xMCAxOToyNzo0MyI7fXM6MzM6IgB5aWlcZGJcQmFzZUFjdGl2ZVJlY29yZABfcmVsYXRlZCI7YToxOntzOjY6Im9wdGlvbiI7cjoxMTA7fXM6MjM6IgB5aWlcYmFzZVxNb2RlbABfZXJyb3JzIjtOO3M6Mjc6IgB5aWlcYmFzZVxNb2RlbABfdmFsaWRhdG9ycyI7TjtzOjI1OiIAeWlpXGJhc2VcTW9kZWwAX3NjZW5hcmlvIjtzOjc6ImRlZmF1bHQiO3M6Mjc6IgB5aWlcYmFzZVxDb21wb25lbnQAX2V2ZW50cyI7YTowOnt9czozMDoiAHlpaVxiYXNlXENvbXBvbmVudABfYmVoYXZpb3JzIjthOjA6e319aTo1O086MjI6ImNvbW1vblxtb2RlbHNcUXVlc3Rpb24iOjk6e3M6MTM6InF1ZXN0aW9uX3RleHQiO3M6NDU6IldoeSBkbyBJIGRvIHRoaXM/IFdoYXQgaXMgdGhlIGJlbmVmaXQgZm9yIG1lPyI7czozNjoiAHlpaVxkYlxCYXNlQWN0aXZlUmVjb3JkAF9hdHRyaWJ1dGVzIjthOjc6e3M6MjoiaWQiO2k6NjQ2O3M6NzoidXNlcl9pZCI7aToyO3M6OToib3B0aW9uX2lkIjtpOjI5O3M6MTQ6InVzZXJfb3B0aW9uX2lkIjtpOjgyMztzOjg6InF1ZXN0aW9uIjtpOjM7czo2OiJhbnN3ZXIiO3M6NToiaWx1YiAiO3M6NDoiZGF0ZSI7czoxOToiMjAxNi0wOS0xMCAxOToyNzo0MyI7fXM6Mzk6IgB5aWlcZGJcQmFzZUFjdGl2ZVJlY29yZABfb2xkQXR0cmlidXRlcyI7YTo3OntzOjI6ImlkIjtpOjY0NjtzOjc6InVzZXJfaWQiO2k6MjtzOjk6Im9wdGlvbl9pZCI7aToyOTtzOjE0OiJ1c2VyX29wdGlvbl9pZCI7aTo4MjM7czo4OiJxdWVzdGlvbiI7aTozO3M6NjoiYW5zd2VyIjtzOjU6ImlsdWIgIjtzOjQ6ImRhdGUiO3M6MTk6IjIwMTYtMDktMTAgMTk6Mjc6NDMiO31zOjMzOiIAeWlpXGRiXEJhc2VBY3RpdmVSZWNvcmQAX3JlbGF0ZWQiO2E6MTp7czo2OiJvcHRpb24iO3I6MTEwO31zOjIzOiIAeWlpXGJhc2VcTW9kZWwAX2Vycm9ycyI7TjtzOjI3OiIAeWlpXGJhc2VcTW9kZWwAX3ZhbGlkYXRvcnMiO047czoyNToiAHlpaVxiYXNlXE1vZGVsAF9zY2VuYXJpbyI7czo3OiJkZWZhdWx0IjtzOjI3OiIAeWlpXGJhc2VcQ29tcG9uZW50AF9ldmVudHMiO2E6MDp7fXM6MzA6IgB5aWlcYmFzZVxDb21wb25lbnQAX2JlaGF2aW9ycyI7YTowOnt9fWk6NjtPOjIyOiJjb21tb25cbW9kZWxzXFF1ZXN0aW9uIjo5OntzOjEzOiJxdWVzdGlvbl90ZXh0IjtzOjQ1OiJIb3cgZG9lcyBpdCBhZmZlY3QgbWU/IEhvdyBkbyBJIGFjdCBhbmQgZmVlbD8iO3M6MzY6IgB5aWlcZGJcQmFzZUFjdGl2ZVJlY29yZABfYXR0cmlidXRlcyI7YTo3OntzOjI6ImlkIjtpOjY0NztzOjc6InVzZXJfaWQiO2k6MjtzOjk6Im9wdGlvbl9pZCI7aTo0ODtzOjE0OiJ1c2VyX29wdGlvbl9pZCI7aTo4MjU7czo4OiJxdWVzdGlvbiI7aToxO3M6NjoiYW5zd2VyIjtzOjQ6ImxpdWIiO3M6NDoiZGF0ZSI7czoxOToiMjAxNi0wOS0xMCAxOToyNzo0MyI7fXM6Mzk6IgB5aWlcZGJcQmFzZUFjdGl2ZVJlY29yZABfb2xkQXR0cmlidXRlcyI7YTo3OntzOjI6ImlkIjtpOjY0NztzOjc6InVzZXJfaWQiO2k6MjtzOjk6Im9wdGlvbl9pZCI7aTo0ODtzOjE0OiJ1c2VyX29wdGlvbl9pZCI7aTo4MjU7czo4OiJxdWVzdGlvbiI7aToxO3M6NjoiYW5zd2VyIjtzOjQ6ImxpdWIiO3M6NDoiZGF0ZSI7czoxOToiMjAxNi0wOS0xMCAxOToyNzo0MyI7fXM6MzM6IgB5aWlcZGJcQmFzZUFjdGl2ZVJlY29yZABfcmVsYXRlZCI7YToxOntzOjY6Im9wdGlvbiI7TzoyMDoiY29tbW9uXG1vZGVsc1xPcHRpb24iOjg6e3M6MzY6IgB5aWlcZGJcQmFzZUFjdGl2ZVJlY29yZABfYXR0cmlidXRlcyI7YTozOntzOjI6ImlkIjtpOjQ4O3M6NDoibmFtZSI7czoxMDoid29ya2Fob2xpYyI7czoxMToiY2F0ZWdvcnlfaWQiO2k6NDt9czozOToiAHlpaVxkYlxCYXNlQWN0aXZlUmVjb3JkAF9vbGRBdHRyaWJ1dGVzIjthOjM6e3M6MjoiaWQiO2k6NDg7czo0OiJuYW1lIjtzOjEwOiJ3b3JrYWhvbGljIjtzOjExOiJjYXRlZ29yeV9pZCI7aTo0O31zOjMzOiIAeWlpXGRiXEJhc2VBY3RpdmVSZWNvcmQAX3JlbGF0ZWQiO2E6MDp7fXM6MjM6IgB5aWlcYmFzZVxNb2RlbABfZXJyb3JzIjtOO3M6Mjc6IgB5aWlcYmFzZVxNb2RlbABfdmFsaWRhdG9ycyI7TjtzOjI1OiIAeWlpXGJhc2VcTW9kZWwAX3NjZW5hcmlvIjtzOjc6ImRlZmF1bHQiO3M6Mjc6IgB5aWlcYmFzZVxDb21wb25lbnQAX2V2ZW50cyI7YTowOnt9czozMDoiAHlpaVxiYXNlXENvbXBvbmVudABfYmVoYXZpb3JzIjthOjA6e319fXM6MjM6IgB5aWlcYmFzZVxNb2RlbABfZXJyb3JzIjtOO3M6Mjc6IgB5aWlcYmFzZVxNb2RlbABfdmFsaWRhdG9ycyI7TjtzOjI1OiIAeWlpXGJhc2VcTW9kZWwAX3NjZW5hcmlvIjtzOjc6ImRlZmF1bHQiO3M6Mjc6IgB5aWlcYmFzZVxDb21wb25lbnQAX2V2ZW50cyI7YTowOnt9czozMDoiAHlpaVxiYXNlXENvbXBvbmVudABfYmVoYXZpb3JzIjthOjA6e319aTo3O086MjI6ImNvbW1vblxtb2RlbHNcUXVlc3Rpb24iOjk6e3M6MTM6InF1ZXN0aW9uX3RleHQiO3M6NTE6IkhvdyBkb2VzIGl0IGFmZmVjdCB0aGUgaW1wb3J0YW50IHBlb3BsZSBpbiBteSBsaWZlPyI7czozNjoiAHlpaVxkYlxCYXNlQWN0aXZlUmVjb3JkAF9hdHRyaWJ1dGVzIjthOjc6e3M6MjoiaWQiO2k6NjQ4O3M6NzoidXNlcl9pZCI7aToyO3M6OToib3B0aW9uX2lkIjtpOjQ4O3M6MTQ6InVzZXJfb3B0aW9uX2lkIjtpOjgyNTtzOjg6InF1ZXN0aW9uIjtpOjI7czo2OiJhbnN3ZXIiO3M6NDoibGl1YiI7czo0OiJkYXRlIjtzOjE5OiIyMDE2LTA5LTEwIDE5OjI3OjQzIjt9czozOToiAHlpaVxkYlxCYXNlQWN0aXZlUmVjb3JkAF9vbGRBdHRyaWJ1dGVzIjthOjc6e3M6MjoiaWQiO2k6NjQ4O3M6NzoidXNlcl9pZCI7aToyO3M6OToib3B0aW9uX2lkIjtpOjQ4O3M6MTQ6InVzZXJfb3B0aW9uX2lkIjtpOjgyNTtzOjg6InF1ZXN0aW9uIjtpOjI7czo2OiJhbnN3ZXIiO3M6NDoibGl1YiI7czo0OiJkYXRlIjtzOjE5OiIyMDE2LTA5LTEwIDE5OjI3OjQzIjt9czozMzoiAHlpaVxkYlxCYXNlQWN0aXZlUmVjb3JkAF9yZWxhdGVkIjthOjE6e3M6Njoib3B0aW9uIjtyOjE5OTt9czoyMzoiAHlpaVxiYXNlXE1vZGVsAF9lcnJvcnMiO047czoyNzoiAHlpaVxiYXNlXE1vZGVsAF92YWxpZGF0b3JzIjtOO3M6MjU6IgB5aWlcYmFzZVxNb2RlbABfc2NlbmFyaW8iO3M6NzoiZGVmYXVsdCI7czoyNzoiAHlpaVxiYXNlXENvbXBvbmVudABfZXZlbnRzIjthOjA6e31zOjMwOiIAeWlpXGJhc2VcQ29tcG9uZW50AF9iZWhhdmlvcnMiO2E6MDp7fX1pOjg7TzoyMjoiY29tbW9uXG1vZGVsc1xRdWVzdGlvbiI6OTp7czoxMzoicXVlc3Rpb25fdGV4dCI7czo0NToiV2h5IGRvIEkgZG8gdGhpcz8gV2hhdCBpcyB0aGUgYmVuZWZpdCBmb3IgbWU/IjtzOjM2OiIAeWlpXGRiXEJhc2VBY3RpdmVSZWNvcmQAX2F0dHJpYnV0ZXMiO2E6Nzp7czoyOiJpZCI7aTo2NDk7czo3OiJ1c2VyX2lkIjtpOjI7czo5OiJvcHRpb25faWQiO2k6NDg7czoxNDoidXNlcl9vcHRpb25faWQiO2k6ODI1O3M6ODoicXVlc3Rpb24iO2k6MztzOjY6ImFuc3dlciI7czo1OiJsaXViICI7czo0OiJkYXRlIjtzOjE5OiIyMDE2LTA5LTEwIDE5OjI3OjQzIjt9czozOToiAHlpaVxkYlxCYXNlQWN0aXZlUmVjb3JkAF9vbGRBdHRyaWJ1dGVzIjthOjc6e3M6MjoiaWQiO2k6NjQ5O3M6NzoidXNlcl9pZCI7aToyO3M6OToib3B0aW9uX2lkIjtpOjQ4O3M6MTQ6InVzZXJfb3B0aW9uX2lkIjtpOjgyNTtzOjg6InF1ZXN0aW9uIjtpOjM7czo2OiJhbnN3ZXIiO3M6NToibGl1YiAiO3M6NDoiZGF0ZSI7czoxOToiMjAxNi0wOS0xMCAxOToyNzo0MyI7fXM6MzM6IgB5aWlcZGJcQmFzZUFjdGl2ZVJlY29yZABfcmVsYXRlZCI7YToxOntzOjY6Im9wdGlvbiI7cjoxOTk7fXM6MjM6IgB5aWlcYmFzZVxNb2RlbABfZXJyb3JzIjtOO3M6Mjc6IgB5aWlcYmFzZVxNb2RlbABfdmFsaWRhdG9ycyI7TjtzOjI1OiIAeWlpXGJhc2VcTW9kZWwAX3NjZW5hcmlvIjtzOjc6ImRlZmF1bHQiO3M6Mjc6IgB5aWlcYmFzZVxDb21wb25lbnQAX2V2ZW50cyI7YTowOnt9czozMDoiAHlpaVxiYXNlXENvbXBvbmVudABfYmVoYXZpb3JzIjthOjA6e319aTo5O086MjI6ImNvbW1vblxtb2RlbHNcUXVlc3Rpb24iOjk6e3M6MTM6InF1ZXN0aW9uX3RleHQiO3M6NDU6IkhvdyBkb2VzIGl0IGFmZmVjdCBtZT8gSG93IGRvIEkgYWN0IGFuZCBmZWVsPyI7czozNjoiAHlpaVxkYlxCYXNlQWN0aXZlUmVjb3JkAF9hdHRyaWJ1dGVzIjthOjc6e3M6MjoiaWQiO2k6NjUwO3M6NzoidXNlcl9pZCI7aToyO3M6OToib3B0aW9uX2lkIjtpOjg5O3M6MTQ6InVzZXJfb3B0aW9uX2lkIjtpOjgyODtzOjg6InF1ZXN0aW9uIjtpOjE7czo2OiJhbnN3ZXIiO3M6NDoibGl1YiI7czo0OiJkYXRlIjtzOjE5OiIyMDE2LTA5LTEwIDE5OjI3OjQzIjt9czozOToiAHlpaVxkYlxCYXNlQWN0aXZlUmVjb3JkAF9vbGRBdHRyaWJ1dGVzIjthOjc6e3M6MjoiaWQiO2k6NjUwO3M6NzoidXNlcl9pZCI7aToyO3M6OToib3B0aW9uX2lkIjtpOjg5O3M6MTQ6InVzZXJfb3B0aW9uX2lkIjtpOjgyODtzOjg6InF1ZXN0aW9uIjtpOjE7czo2OiJhbnN3ZXIiO3M6NDoibGl1YiI7czo0OiJkYXRlIjtzOjE5OiIyMDE2LTA5LTEwIDE5OjI3OjQzIjt9czozMzoiAHlpaVxkYlxCYXNlQWN0aXZlUmVjb3JkAF9yZWxhdGVkIjthOjE6e3M6Njoib3B0aW9uIjtPOjIwOiJjb21tb25cbW9kZWxzXE9wdGlvbiI6ODp7czozNjoiAHlpaVxkYlxCYXNlQWN0aXZlUmVjb3JkAF9hdHRyaWJ1dGVzIjthOjM6e3M6MjoiaWQiO2k6ODk7czo0OiJuYW1lIjtzOjI2OiJvYnNlc3NpdmUgKHN0dWNrKSB0aG91Z2h0cyI7czoxMToiY2F0ZWdvcnlfaWQiO2k6NTt9czozOToiAHlpaVxkYlxCYXNlQWN0aXZlUmVjb3JkAF9vbGRBdHRyaWJ1dGVzIjthOjM6e3M6MjoiaWQiO2k6ODk7czo0OiJuYW1lIjtzOjI2OiJvYnNlc3NpdmUgKHN0dWNrKSB0aG91Z2h0cyI7czoxMToiY2F0ZWdvcnlfaWQiO2k6NTt9czozMzoiAHlpaVxkYlxCYXNlQWN0aXZlUmVjb3JkAF9yZWxhdGVkIjthOjA6e31zOjIzOiIAeWlpXGJhc2VcTW9kZWwAX2Vycm9ycyI7TjtzOjI3OiIAeWlpXGJhc2VcTW9kZWwAX3ZhbGlkYXRvcnMiO047czoyNToiAHlpaVxiYXNlXE1vZGVsAF9zY2VuYXJpbyI7czo3OiJkZWZhdWx0IjtzOjI3OiIAeWlpXGJhc2VcQ29tcG9uZW50AF9ldmVudHMiO2E6MDp7fXM6MzA6IgB5aWlcYmFzZVxDb21wb25lbnQAX2JlaGF2aW9ycyI7YTowOnt9fX1zOjIzOiIAeWlpXGJhc2VcTW9kZWwAX2Vycm9ycyI7TjtzOjI3OiIAeWlpXGJhc2VcTW9kZWwAX3ZhbGlkYXRvcnMiO047czoyNToiAHlpaVxiYXNlXE1vZGVsAF9zY2VuYXJpbyI7czo3OiJkZWZhdWx0IjtzOjI3OiIAeWlpXGJhc2VcQ29tcG9uZW50AF9ldmVudHMiO2E6MDp7fXM6MzA6IgB5aWlcYmFzZVxDb21wb25lbnQAX2JlaGF2aW9ycyI7YTowOnt9fWk6MTA7TzoyMjoiY29tbW9uXG1vZGVsc1xRdWVzdGlvbiI6OTp7czoxMzoicXVlc3Rpb25fdGV4dCI7czo1MToiSG93IGRvZXMgaXQgYWZmZWN0IHRoZSBpbXBvcnRhbnQgcGVvcGxlIGluIG15IGxpZmU/IjtzOjM2OiIAeWlpXGRiXEJhc2VBY3RpdmVSZWNvcmQAX2F0dHJpYnV0ZXMiO2E6Nzp7czoyOiJpZCI7aTo2NTE7czo3OiJ1c2VyX2lkIjtpOjI7czo5OiJvcHRpb25faWQiO2k6ODk7czoxNDoidXNlcl9vcHRpb25faWQiO2k6ODI4O3M6ODoicXVlc3Rpb24iO2k6MjtzOjY6ImFuc3dlciI7czo1OiJsaXVieSI7czo0OiJkYXRlIjtzOjE5OiIyMDE2LTA5LTEwIDE5OjI3OjQzIjt9czozOToiAHlpaVxkYlxCYXNlQWN0aXZlUmVjb3JkAF9vbGRBdHRyaWJ1dGVzIjthOjc6e3M6MjoiaWQiO2k6NjUxO3M6NzoidXNlcl9pZCI7aToyO3M6OToib3B0aW9uX2lkIjtpOjg5O3M6MTQ6InVzZXJfb3B0aW9uX2lkIjtpOjgyODtzOjg6InF1ZXN0aW9uIjtpOjI7czo2OiJhbnN3ZXIiO3M6NToibGl1YnkiO3M6NDoiZGF0ZSI7czoxOToiMjAxNi0wOS0xMCAxOToyNzo0MyI7fXM6MzM6IgB5aWlcZGJcQmFzZUFjdGl2ZVJlY29yZABfcmVsYXRlZCI7YToxOntzOjY6Im9wdGlvbiI7cjoyODg7fXM6MjM6IgB5aWlcYmFzZVxNb2RlbABfZXJyb3JzIjtOO3M6Mjc6IgB5aWlcYmFzZVxNb2RlbABfdmFsaWRhdG9ycyI7TjtzOjI1OiIAeWlpXGJhc2VcTW9kZWwAX3NjZW5hcmlvIjtzOjc6ImRlZmF1bHQiO3M6Mjc6IgB5aWlcYmFzZVxDb21wb25lbnQAX2V2ZW50cyI7YTowOnt9czozMDoiAHlpaVxiYXNlXENvbXBvbmVudABfYmVoYXZpb3JzIjthOjA6e319aToxMTtPOjIyOiJjb21tb25cbW9kZWxzXFF1ZXN0aW9uIjo5OntzOjEzOiJxdWVzdGlvbl90ZXh0IjtzOjQ1OiJXaHkgZG8gSSBkbyB0aGlzPyBXaGF0IGlzIHRoZSBiZW5lZml0IGZvciBtZT8iO3M6MzY6IgB5aWlcZGJcQmFzZUFjdGl2ZVJlY29yZABfYXR0cmlidXRlcyI7YTo3OntzOjI6ImlkIjtpOjY1MjtzOjc6InVzZXJfaWQiO2k6MjtzOjk6Im9wdGlvbl9pZCI7aTo4OTtzOjE0OiJ1c2VyX29wdGlvbl9pZCI7aTo4Mjg7czo4OiJxdWVzdGlvbiI7aTozO3M6NjoiYW5zd2VyIjtzOjU6InVpeWxiIjtzOjQ6ImRhdGUiO3M6MTk6IjIwMTYtMDktMTAgMTk6Mjc6NDMiO31zOjM5OiIAeWlpXGRiXEJhc2VBY3RpdmVSZWNvcmQAX29sZEF0dHJpYnV0ZXMiO2E6Nzp7czoyOiJpZCI7aTo2NTI7czo3OiJ1c2VyX2lkIjtpOjI7czo5OiJvcHRpb25faWQiO2k6ODk7czoxNDoidXNlcl9vcHRpb25faWQiO2k6ODI4O3M6ODoicXVlc3Rpb24iO2k6MztzOjY6ImFuc3dlciI7czo1OiJ1aXlsYiI7czo0OiJkYXRlIjtzOjE5OiIyMDE2LTA5LTEwIDE5OjI3OjQzIjt9czozMzoiAHlpaVxkYlxCYXNlQWN0aXZlUmVjb3JkAF9yZWxhdGVkIjthOjE6e3M6Njoib3B0aW9uIjtyOjI4ODt9czoyMzoiAHlpaVxiYXNlXE1vZGVsAF9lcnJvcnMiO047czoyNzoiAHlpaVxiYXNlXE1vZGVsAF92YWxpZGF0b3JzIjtOO3M6MjU6IgB5aWlcYmFzZVxNb2RlbABfc2NlbmFyaW8iO3M6NzoiZGVmYXVsdCI7czoyNzoiAHlpaVxiYXNlXENvbXBvbmVudABfZXZlbnRzIjthOjA6e31zOjMwOiIAeWlpXGJhc2VcQ29tcG9uZW50AF9iZWhhdmlvcnMiO2E6MDp7fX1pOjEyO086MjI6ImNvbW1vblxtb2RlbHNcUXVlc3Rpb24iOjk6e3M6MTM6InF1ZXN0aW9uX3RleHQiO3M6NDU6IkhvdyBkb2VzIGl0IGFmZmVjdCBtZT8gSG93IGRvIEkgYWN0IGFuZCBmZWVsPyI7czozNjoiAHlpaVxkYlxCYXNlQWN0aXZlUmVjb3JkAF9hdHRyaWJ1dGVzIjthOjc6e3M6MjoiaWQiO2k6NjUzO3M6NzoidXNlcl9pZCI7aToyO3M6OToib3B0aW9uX2lkIjtpOjExMTtzOjE0OiJ1c2VyX29wdGlvbl9pZCI7aTo4Mjk7czo4OiJxdWVzdGlvbiI7aToxO3M6NjoiYW5zd2VyIjtzOjQ6ImxpdWIiO3M6NDoiZGF0ZSI7czoxOToiMjAxNi0wOS0xMCAxOToyNzo0MyI7fXM6Mzk6IgB5aWlcZGJcQmFzZUFjdGl2ZVJlY29yZABfb2xkQXR0cmlidXRlcyI7YTo3OntzOjI6ImlkIjtpOjY1MztzOjc6InVzZXJfaWQiO2k6MjtzOjk6Im9wdGlvbl9pZCI7aToxMTE7czoxNDoidXNlcl9vcHRpb25faWQiO2k6ODI5O3M6ODoicXVlc3Rpb24iO2k6MTtzOjY6ImFuc3dlciI7czo0OiJsaXViIjtzOjQ6ImRhdGUiO3M6MTk6IjIwMTYtMDktMTAgMTk6Mjc6NDMiO31zOjMzOiIAeWlpXGRiXEJhc2VBY3RpdmVSZWNvcmQAX3JlbGF0ZWQiO2E6MTp7czo2OiJvcHRpb24iO086MjA6ImNvbW1vblxtb2RlbHNcT3B0aW9uIjo4OntzOjM2OiIAeWlpXGRiXEJhc2VBY3RpdmVSZWNvcmQAX2F0dHJpYnV0ZXMiO2E6Mzp7czoyOiJpZCI7aToxMTE7czo0OiJuYW1lIjtzOjQzOiJzZWVraW5nIG91dCBvbGQgdW5oZWFsdGh5IHBlb3BsZSBhbmQgcGxhY2VzIjtzOjExOiJjYXRlZ29yeV9pZCI7aTo2O31zOjM5OiIAeWlpXGRiXEJhc2VBY3RpdmVSZWNvcmQAX29sZEF0dHJpYnV0ZXMiO2E6Mzp7czoyOiJpZCI7aToxMTE7czo0OiJuYW1lIjtzOjQzOiJzZWVraW5nIG91dCBvbGQgdW5oZWFsdGh5IHBlb3BsZSBhbmQgcGxhY2VzIjtzOjExOiJjYXRlZ29yeV9pZCI7aTo2O31zOjMzOiIAeWlpXGRiXEJhc2VBY3RpdmVSZWNvcmQAX3JlbGF0ZWQiO2E6MDp7fXM6MjM6IgB5aWlcYmFzZVxNb2RlbABfZXJyb3JzIjtOO3M6Mjc6IgB5aWlcYmFzZVxNb2RlbABfdmFsaWRhdG9ycyI7TjtzOjI1OiIAeWlpXGJhc2VcTW9kZWwAX3NjZW5hcmlvIjtzOjc6ImRlZmF1bHQiO3M6Mjc6IgB5aWlcYmFzZVxDb21wb25lbnQAX2V2ZW50cyI7YTowOnt9czozMDoiAHlpaVxiYXNlXENvbXBvbmVudABfYmVoYXZpb3JzIjthOjA6e319fXM6MjM6IgB5aWlcYmFzZVxNb2RlbABfZXJyb3JzIjtOO3M6Mjc6IgB5aWlcYmFzZVxNb2RlbABfdmFsaWRhdG9ycyI7TjtzOjI1OiIAeWlpXGJhc2VcTW9kZWwAX3NjZW5hcmlvIjtzOjc6ImRlZmF1bHQiO3M6Mjc6IgB5aWlcYmFzZVxDb21wb25lbnQAX2V2ZW50cyI7YTowOnt9czozMDoiAHlpaVxiYXNlXENvbXBvbmVudABfYmVoYXZpb3JzIjthOjA6e319aToxMztPOjIyOiJjb21tb25cbW9kZWxzXFF1ZXN0aW9uIjo5OntzOjEzOiJxdWVzdGlvbl90ZXh0IjtzOjUxOiJIb3cgZG9lcyBpdCBhZmZlY3QgdGhlIGltcG9ydGFudCBwZW9wbGUgaW4gbXkgbGlmZT8iO3M6MzY6IgB5aWlcZGJcQmFzZUFjdGl2ZVJlY29yZABfYXR0cmlidXRlcyI7YTo3OntzOjI6ImlkIjtpOjY1NDtzOjc6InVzZXJfaWQiO2k6MjtzOjk6Im9wdGlvbl9pZCI7aToxMTE7czoxNDoidXNlcl9vcHRpb25faWQiO2k6ODI5O3M6ODoicXVlc3Rpb24iO2k6MjtzOjY6ImFuc3dlciI7czo1OiJsaXV5YiI7czo0OiJkYXRlIjtzOjE5OiIyMDE2LTA5LTEwIDE5OjI3OjQzIjt9czozOToiAHlpaVxkYlxCYXNlQWN0aXZlUmVjb3JkAF9vbGRBdHRyaWJ1dGVzIjthOjc6e3M6MjoiaWQiO2k6NjU0O3M6NzoidXNlcl9pZCI7aToyO3M6OToib3B0aW9uX2lkIjtpOjExMTtzOjE0OiJ1c2VyX29wdGlvbl9pZCI7aTo4Mjk7czo4OiJxdWVzdGlvbiI7aToyO3M6NjoiYW5zd2VyIjtzOjU6ImxpdXliIjtzOjQ6ImRhdGUiO3M6MTk6IjIwMTYtMDktMTAgMTk6Mjc6NDMiO31zOjMzOiIAeWlpXGRiXEJhc2VBY3RpdmVSZWNvcmQAX3JlbGF0ZWQiO2E6MTp7czo2OiJvcHRpb24iO3I6Mzc3O31zOjIzOiIAeWlpXGJhc2VcTW9kZWwAX2Vycm9ycyI7TjtzOjI3OiIAeWlpXGJhc2VcTW9kZWwAX3ZhbGlkYXRvcnMiO047czoyNToiAHlpaVxiYXNlXE1vZGVsAF9zY2VuYXJpbyI7czo3OiJkZWZhdWx0IjtzOjI3OiIAeWlpXGJhc2VcQ29tcG9uZW50AF9ldmVudHMiO2E6MDp7fXM6MzA6IgB5aWlcYmFzZVxDb21wb25lbnQAX2JlaGF2aW9ycyI7YTowOnt9fWk6MTQ7TzoyMjoiY29tbW9uXG1vZGVsc1xRdWVzdGlvbiI6OTp7czoxMzoicXVlc3Rpb25fdGV4dCI7czo0NToiV2h5IGRvIEkgZG8gdGhpcz8gV2hhdCBpcyB0aGUgYmVuZWZpdCBmb3IgbWU/IjtzOjM2OiIAeWlpXGRiXEJhc2VBY3RpdmVSZWNvcmQAX2F0dHJpYnV0ZXMiO2E6Nzp7czoyOiJpZCI7aTo2NTU7czo3OiJ1c2VyX2lkIjtpOjI7czo5OiJvcHRpb25faWQiO2k6MTExO3M6MTQ6InVzZXJfb3B0aW9uX2lkIjtpOjgyOTtzOjg6InF1ZXN0aW9uIjtpOjM7czo2OiJhbnN3ZXIiO3M6NDoiaXV5YiI7czo0OiJkYXRlIjtzOjE5OiIyMDE2LTA5LTEwIDE5OjI3OjQzIjt9czozOToiAHlpaVxkYlxCYXNlQWN0aXZlUmVjb3JkAF9vbGRBdHRyaWJ1dGVzIjthOjc6e3M6MjoiaWQiO2k6NjU1O3M6NzoidXNlcl9pZCI7aToyO3M6OToib3B0aW9uX2lkIjtpOjExMTtzOjE0OiJ1c2VyX29wdGlvbl9pZCI7aTo4Mjk7czo4OiJxdWVzdGlvbiI7aTozO3M6NjoiYW5zd2VyIjtzOjQ6Iml1eWIiO3M6NDoiZGF0ZSI7czoxOToiMjAxNi0wOS0xMCAxOToyNzo0MyI7fXM6MzM6IgB5aWlcZGJcQmFzZUFjdGl2ZVJlY29yZABfcmVsYXRlZCI7YToxOntzOjY6Im9wdGlvbiI7cjozNzc7fXM6MjM6IgB5aWlcYmFzZVxNb2RlbABfZXJyb3JzIjtOO3M6Mjc6IgB5aWlcYmFzZVxNb2RlbABfdmFsaWRhdG9ycyI7TjtzOjI1OiIAeWlpXGJhc2VcTW9kZWwAX3NjZW5hcmlvIjtzOjc6ImRlZmF1bHQiO3M6Mjc6IgB5aWlcYmFzZVxDb21wb25lbnQAX2V2ZW50cyI7YTowOnt9czozMDoiAHlpaVxiYXNlXENvbXBvbmVudABfYmVoYXZpb3JzIjthOjA6e319aToxNTtPOjIyOiJjb21tb25cbW9kZWxzXFF1ZXN0aW9uIjo5OntzOjEzOiJxdWVzdGlvbl90ZXh0IjtzOjQ1OiJIb3cgZG9lcyBpdCBhZmZlY3QgbWU/IEhvdyBkbyBJIGFjdCBhbmQgZmVlbD8iO3M6MzY6IgB5aWlcZGJcQmFzZUFjdGl2ZVJlY29yZABfYXR0cmlidXRlcyI7YTo3OntzOjI6ImlkIjtpOjY1NjtzOjc6InVzZXJfaWQiO2k6MjtzOjk6Im9wdGlvbl9pZCI7aToxMjI7czoxNDoidXNlcl9vcHRpb25faWQiO2k6ODMxO3M6ODoicXVlc3Rpb24iO2k6MTtzOjY6ImFuc3dlciI7czo0OiJpdXliIjtzOjQ6ImRhdGUiO3M6MTk6IjIwMTYtMDktMTAgMTk6Mjc6NDMiO31zOjM5OiIAeWlpXGRiXEJhc2VBY3RpdmVSZWNvcmQAX29sZEF0dHJpYnV0ZXMiO2E6Nzp7czoyOiJpZCI7aTo2NTY7czo3OiJ1c2VyX2lkIjtpOjI7czo5OiJvcHRpb25faWQiO2k6MTIyO3M6MTQ6InVzZXJfb3B0aW9uX2lkIjtpOjgzMTtzOjg6InF1ZXN0aW9uIjtpOjE7czo2OiJhbnN3ZXIiO3M6NDoiaXV5YiI7czo0OiJkYXRlIjtzOjE5OiIyMDE2LTA5LTEwIDE5OjI3OjQzIjt9czozMzoiAHlpaVxkYlxCYXNlQWN0aXZlUmVjb3JkAF9yZWxhdGVkIjthOjE6e3M6Njoib3B0aW9uIjtPOjIwOiJjb21tb25cbW9kZWxzXE9wdGlvbiI6ODp7czozNjoiAHlpaVxkYlxCYXNlQWN0aXZlUmVjb3JkAF9hdHRyaWJ1dGVzIjthOjM6e3M6MjoiaWQiO2k6MTIyO3M6NDoibmFtZSI7czo1NzoicmV0dXJuaW5nIHRvIHRoZSBwbGFjZSB5b3Ugc3dvcmUgeW91IHdvdWxkIG5ldmVyIGdvIGFnYWluIjtzOjExOiJjYXRlZ29yeV9pZCI7aTo3O31zOjM5OiIAeWlpXGRiXEJhc2VBY3RpdmVSZWNvcmQAX29sZEF0dHJpYnV0ZXMiO2E6Mzp7czoyOiJpZCI7aToxMjI7czo0OiJuYW1lIjtzOjU3OiJyZXR1cm5pbmcgdG8gdGhlIHBsYWNlIHlvdSBzd29yZSB5b3Ugd291bGQgbmV2ZXIgZ28gYWdhaW4iO3M6MTE6ImNhdGVnb3J5X2lkIjtpOjc7fXM6MzM6IgB5aWlcZGJcQmFzZUFjdGl2ZVJlY29yZABfcmVsYXRlZCI7YTowOnt9czoyMzoiAHlpaVxiYXNlXE1vZGVsAF9lcnJvcnMiO047czoyNzoiAHlpaVxiYXNlXE1vZGVsAF92YWxpZGF0b3JzIjtOO3M6MjU6IgB5aWlcYmFzZVxNb2RlbABfc2NlbmFyaW8iO3M6NzoiZGVmYXVsdCI7czoyNzoiAHlpaVxiYXNlXENvbXBvbmVudABfZXZlbnRzIjthOjA6e31zOjMwOiIAeWlpXGJhc2VcQ29tcG9uZW50AF9iZWhhdmlvcnMiO2E6MDp7fX19czoyMzoiAHlpaVxiYXNlXE1vZGVsAF9lcnJvcnMiO047czoyNzoiAHlpaVxiYXNlXE1vZGVsAF92YWxpZGF0b3JzIjtOO3M6MjU6IgB5aWlcYmFzZVxNb2RlbABfc2NlbmFyaW8iO3M6NzoiZGVmYXVsdCI7czoyNzoiAHlpaVxiYXNlXENvbXBvbmVudABfZXZlbnRzIjthOjA6e31zOjMwOiIAeWlpXGJhc2VcQ29tcG9uZW50AF9iZWhhdmlvcnMiO2E6MDp7fX1pOjE2O086MjI6ImNvbW1vblxtb2RlbHNcUXVlc3Rpb24iOjk6e3M6MTM6InF1ZXN0aW9uX3RleHQiO3M6NTE6IkhvdyBkb2VzIGl0IGFmZmVjdCB0aGUgaW1wb3J0YW50IHBlb3BsZSBpbiBteSBsaWZlPyI7czozNjoiAHlpaVxkYlxCYXNlQWN0aXZlUmVjb3JkAF9hdHRyaWJ1dGVzIjthOjc6e3M6MjoiaWQiO2k6NjU3O3M6NzoidXNlcl9pZCI7aToyO3M6OToib3B0aW9uX2lkIjtpOjEyMjtzOjE0OiJ1c2VyX29wdGlvbl9pZCI7aTo4MzE7czo4OiJxdWVzdGlvbiI7aToyO3M6NjoiYW5zd2VyIjtzOjQ6Iml1eWIiO3M6NDoiZGF0ZSI7czoxOToiMjAxNi0wOS0xMCAxOToyNzo0MyI7fXM6Mzk6IgB5aWlcZGJcQmFzZUFjdGl2ZVJlY29yZABfb2xkQXR0cmlidXRlcyI7YTo3OntzOjI6ImlkIjtpOjY1NztzOjc6InVzZXJfaWQiO2k6MjtzOjk6Im9wdGlvbl9pZCI7aToxMjI7czoxNDoidXNlcl9vcHRpb25faWQiO2k6ODMxO3M6ODoicXVlc3Rpb24iO2k6MjtzOjY6ImFuc3dlciI7czo0OiJpdXliIjtzOjQ6ImRhdGUiO3M6MTk6IjIwMTYtMDktMTAgMTk6Mjc6NDMiO31zOjMzOiIAeWlpXGRiXEJhc2VBY3RpdmVSZWNvcmQAX3JlbGF0ZWQiO2E6MTp7czo2OiJvcHRpb24iO3I6NDY2O31zOjIzOiIAeWlpXGJhc2VcTW9kZWwAX2Vycm9ycyI7TjtzOjI3OiIAeWlpXGJhc2VcTW9kZWwAX3ZhbGlkYXRvcnMiO047czoyNToiAHlpaVxiYXNlXE1vZGVsAF9zY2VuYXJpbyI7czo3OiJkZWZhdWx0IjtzOjI3OiIAeWlpXGJhc2VcQ29tcG9uZW50AF9ldmVudHMiO2E6MDp7fXM6MzA6IgB5aWlcYmFzZVxDb21wb25lbnQAX2JlaGF2aW9ycyI7YTowOnt9fWk6MTc7TzoyMjoiY29tbW9uXG1vZGVsc1xRdWVzdGlvbiI6OTp7czoxMzoicXVlc3Rpb25fdGV4dCI7czo0NToiV2h5IGRvIEkgZG8gdGhpcz8gV2hhdCBpcyB0aGUgYmVuZWZpdCBmb3IgbWU/IjtzOjM2OiIAeWlpXGRiXEJhc2VBY3RpdmVSZWNvcmQAX2F0dHJpYnV0ZXMiO2E6Nzp7czoyOiJpZCI7aTo2NTg7czo3OiJ1c2VyX2lkIjtpOjI7czo5OiJvcHRpb25faWQiO2k6MTIyO3M6MTQ6InVzZXJfb3B0aW9uX2lkIjtpOjgzMTtzOjg6InF1ZXN0aW9uIjtpOjM7czo2OiJhbnN3ZXIiO3M6NToibGl1eWIiO3M6NDoiZGF0ZSI7czoxOToiMjAxNi0wOS0xMCAxOToyNzo0MyI7fXM6Mzk6IgB5aWlcZGJcQmFzZUFjdGl2ZVJlY29yZABfb2xkQXR0cmlidXRlcyI7YTo3OntzOjI6ImlkIjtpOjY1ODtzOjc6InVzZXJfaWQiO2k6MjtzOjk6Im9wdGlvbl9pZCI7aToxMjI7czoxNDoidXNlcl9vcHRpb25faWQiO2k6ODMxO3M6ODoicXVlc3Rpb24iO2k6MztzOjY6ImFuc3dlciI7czo1OiJsaXV5YiI7czo0OiJkYXRlIjtzOjE5OiIyMDE2LTA5LTEwIDE5OjI3OjQzIjt9czozMzoiAHlpaVxkYlxCYXNlQWN0aXZlUmVjb3JkAF9yZWxhdGVkIjthOjE6e3M6Njoib3B0aW9uIjtyOjQ2Njt9czoyMzoiAHlpaVxiYXNlXE1vZGVsAF9lcnJvcnMiO047czoyNzoiAHlpaVxiYXNlXE1vZGVsAF92YWxpZGF0b3JzIjtOO3M6MjU6IgB5aWlcYmFzZVxNb2RlbABfc2NlbmFyaW8iO3M6NzoiZGVmYXVsdCI7czoyNzoiAHlpaVxiYXNlXENvbXBvbmVudABfZXZlbnRzIjthOjA6e31zOjMwOiIAeWlpXGJhc2VcQ29tcG9uZW50AF9iZWhhdmlvcnMiO2E6MDp7fX19';

public $userQuestions = [
	13 => [
		'question' => [
			'id' => 13,
			'title' => 'less time/energy for God, meetings, and church',
		],
		'answers' => [
			[
				'title' => 'How does it affect me? How do I act and feel?',
				'answer' => 'alsgn',
			], [
				'title' => 'How does it affect the important people in my life?',
				'answer' => 'loiun',
			], [
				'title' => 'Why do I do this? What is the benefit for me?',
				'answer' => 'liun',
			],
		],
	],
	29 => [
		'question' => [
			'id' => 29,
			'title' => 'using profanity',
		],
		'answers' => [
			[
				'title' => 'How does it affect me? How do I act and feel?',
				'answer' => 'ljnb',
			], [
				'title' => 'How does it affect the important people in my life?',
				'answer' => 'liunb',
			], [
				'title' => 'Why do I do this? What is the benefit for me?',
				'answer' => 'ilub ',
			],
		],
	],
	48 => [
		'question' => [
			'id' => 48,
			'title' => 'workaholic',
		],
		'answers' => [
			[
				'title' => 'How does it affect me? How do I act and feel?',
				'answer' => 'liub',
			], [
				'title' => 'How does it affect the important people in my life?',
				'answer' => 'liub',
			], [
				'title' => 'Why do I do this? What is the benefit for me?',
				'answer' => 'liub ',
			],
		],
	],
	89 => [
		'question' => [
			'id' => 89,
			'title' => 'obsessive (stuck) thoughts',
		],
		'answers' => [
			[
				'title' => 'How does it affect me? How do I act and feel?',
				'answer' => 'liub',
			], [
				'title' => 'How does it affect the important people in my life?',
				'answer' => 'liuby',
			], [
				'title' => 'Why do I do this? What is the benefit for me?',
				'answer' => 'uiylb',
			],
		],
	],
	111 => [
		'question' => [
			'id' => 111,
			'title' => 'seeking out old unhealthy people and places',
		],
		'answers' => [
			[
				'title' => 'How does it affect me? How do I act and feel?',
				'answer' => 'liub',
			], [
				'title' => 'How does it affect the important people in my life?',
				'answer' => 'liuyb',
			], [
				'title' => 'Why do I do this? What is the benefit for me?',
				'answer' => 'iuyb',
			],
		],
	],
	122 => [
		'question' => [
			'id' => 122,
			'title' => 'returning to the place you swore you would never go again',
		],
		'answers' => [
			[
				'title' => 'How does it affect me? How do I act and feel?',
				'answer' => 'iuyb',
			], [
				'title' => 'How does it affect the important people in my life?',
				'answer' => 'iuyb',
			], [
				'title' => 'Why do I do this? What is the benefit for me?',
				'answer' => 'liuyb',
			],
		],
	],
];

public $optionData = [
	[
		'id' => 820,
		'user_id' => 2,
		'option_id' => 7,
		'date' => '2016-09-10 19:26:04',
		'option' => [
			'id' => 7,
			'name' => 'making eye contact',
			'category_id' => 1,
			'category' => [
				'id' => 1,
				'name' => 'Restoration',
				'weight' => '0',
			],
		],
	], [
		'id' => 821,
		'user_id' => 2,
		'option_id' => 13,
		'date' => '2016-09-10 19:26:04',
		'option' => [
			'id' => 13,
			'name' => 'less time/energy for God, meetings, and church',
			'category_id' => 2,
			'category' => [
				'id' => 2,
				'name' => 'Forgetting Priorities',
				'weight' => '0.016',
			],
		],
	], [
		'id' => 822,
		'user_id' => 2,
		'option_id' => 18,
		'date' => '2016-09-10 19:26:04',
		'option' => [
			'id' => 18,
			'name' => 'changes in goals',
			'category_id' => 2,
			'category' => [
				'id' => 2,
				'name' => 'Forgetting Priorities',
				'weight' => '0.016',
			],
		],
	], [
		'id' => 823,
		'user_id' => 2,
		'option_id' => 29,
		'date' => '2016-09-10 19:26:04',
		'option' => [
			'id' => 29,
			'name' => 'using profanity',
			'category_id' => 3,
			'category' => [
				'id' => 3,
				'name' => 'Anxiety',
				'weight' => '0.032',
			],
		],
	], [
		'id' => 824,
		'user_id' => 2,
		'option_id' => 41,
		'date' => '2016-09-10 19:26:04',
		'option' => [
			'id' => 41,
			'name' => 'co-dependent rescuing',
			'category_id' => 3,
			'category' => [
				'id' => 3,
				'name' => 'Anxiety',
				'weight' => '0.032',
			],
		],
	], [
		'id' => 825,
		'user_id' => 2,
		'option_id' => 48,
		'date' => '2016-09-10 19:26:04',
		'option' => [
			'id' => 48,
			'name' => 'workaholic',
			'category_id' => 4,
			'category' => [
				'id' => 4,
				'name' => 'Speeding Up',
				'weight' => '0.064',
			],
		],
	], [
		'id' => 826,
		'user_id' => 2,
		'option_id' => 72,
		'date' => '2016-09-10 19:26:04',
		'option' => [
			'id' => 72,
			'name' => 'black and white, all or nothing thinking',
			'category_id' => 5,
			'category' => [
				'id' => 5,
				'name' => 'Ticked Off',
				'weight' => '0.128',
			],
		],
	], [
		'id' => 827,
		'user_id' => 2,
		'option_id' => 79,
		'date' => '2016-09-10 19:26:04',
		'option' => [
			'id' => 79,
			'name' => 'blaming',
			'category_id' => 5,
			'category' => [
				'id' => 5,
				'name' => 'Ticked Off',
				'weight' => '0.128',
			],
		],
	], [
		'id' => 828,
		'user_id' => 2,
		'option_id' => 89,
		'date' => '2016-09-10 19:26:04',
		'option' => [
			'id' => 89,
			'name' => 'obsessive (stuck] thoughts',
			'category_id' => 5,
			'category' => [
				'id' => 5,
				'name' => 'Ticked Off',
				'weight' => '0.128',
			],
		],
	], [
		'id' => 829,
		'user_id' => 2,
		'option_id' => 111,
		'date' => '2016-09-10 19:26:04',
		'option' => [
			'id' => 111,
			'name' => 'seeking out old unhealthy people and places',
			'category_id' => 6,
			'category' => [
				'id' => 6,
				'name' => 'Exhausted',
				'weight' => '0.256',
			],
		],
	], [
		'id' => 830,
		'user_id' => 2,
		'option_id' => 118,
		'date' => '2016-09-10 19:26:04',
		'option' => [
			'id' => 118,
			'name' => 'not returning phone calls',
			'category_id' => 6,
			'category' => [
				'id' => 6,
				'name' => 'Exhausted',
				'weight' => '0.256',
			],
		],
	], [
		'id' => 831,
		'user_id' => 2,
		'option_id' => 122,
		'date' => '2016-09-10 19:26:04',
		'option' => [
			'id' => 122,
			'name' => 'returning to the place you swore you would never go again',
			'category_id' => 7,
			'category' => [
				'id' => 7,
				'name' => 'Relapse/Moral Failure',
				'weight' => '0.512',
			],
		],
	],
];

public $userOptions = [
	[
		'category_name' => 'Restoration',
		'options' => [
			[
				'id' => 7,
				'name' => 'making eye contact',
			],
		],
	], [
		'category_name' => 'Forgetting Priorities',
		'options' => [
			[
				'id' => 13,
				'name' => 'less time/energy for God, meetings, and church',
			], [
				'id' => 18,
				'name' => 'changes in goals',
			],
		],
	], [
		'category_name' => 'Anxiety',
		'options' => [
			[
				'id' => 29,
				'name' => 'using profanity',
			], [
				'id' => 41,
				'name' => 'co-dependent rescuing',
			],
		],
	], [
		'category_name' => 'Speeding Up',
		'options' => [
			[
				'id' => 48,
				'name' => 'workaholic',
			],
		],
	], [
		'category_name' => 'Ticked Off',
		'options' => [
			[
				'id' => 72,
				'name' => 'black and white, all or nothing thinking',
			], [
				'id' => 79,
				'name' => 'blaming',
			], [
				'id' => 89,
				'name' => 'obsessive (stuck] thoughts',
			],
		],
	], [
		'category_name' => 'Exhausted',
		'options' => [
			[
				'id' => 111,
				'name' => 'seeking out old unhealthy people and places',
			], [
				'id' => 118,
				'name' => 'not returning phone calls',
			],
		],
	], [
		'category_name' => 'Relapse/Moral Failure',
		'options' => [
			[
				'id' => 122,
				'name' => 'returning to the place you swore you would never go again',
			],
		],
	],
];

  public function setUp() {
    parent::setUp();

    $this->questionData = unserialize(base64_decode($this->questionData));
  }

  protected function tearDown() {
    parent::tearDown();
  }

  public function testGetUserQuestions() {
    $this->specify('getUserQuestions should function correctly', function () {
      expect('getUserQuestions should return the correct structure with expected data', $this->assertEquals(User::getUserQuestions('2016-09-10', $this->questionData), $this->userQuestions));
      expect('getUserQuestions should return empty with the empty set', $this->assertEmpty(User::getUserQuestions('2016-09-10', [])));
    });
  }

  public function testGetUserOptions() {
    $this->specify('getUserOptions should function correctly', function () {
      expect('getUserOptions should return the correct structure with expected data', $this->assertEquals(User::getUserOptions('2016-09-10', $this->optionData), $this->userOptions));
      expect('getUserOptions should return empty with the empty set', $this->assertEmpty(User::getUserOptions('2016-09-10', [])));
    });
  }
}
