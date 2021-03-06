<?php

namespace Tests\Browser;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CommentTest extends DuskTestCase
{
    /**
     * Ensure we can comment on a post
     */
    public function testComment()
    {
        $user0 = factory(User::class)->create([
            'password' => bcrypt('password') // Create an user 0 with specific password "password"
        ]);
		
        $this->browse(function (Browser $browser) use ($user0) {
			
            $browser->visit('/login') // go to login page
					->type('email',$user0->email) // type in user email
					->type('password','password') // type in user password
					->press('Login') // press login button
					->visit('/posts/create') // go to post creation page
                    ->type('body','Test body') // type in a body
                    ->type('title','Test Title') // type in a title
                    ->attach('image','./tests/TestImages/test.png') // attach a test image to the form
					->press('Submit') // submit the form
					->visit('/posts/1') // go to the post's page
					->type('message','Test comment') // type a comment
					->press('SUBMIT') // submit the comment form
					->assertSee('Test comment'); // assert that the comment has been posted
					
        });
    }
    /**
     * Ensure a user can reply to another user's comment
     */
    public function testReply()
    {
        $user1 = factory(User::class)->create([
            'password' => bcrypt('password') // Create an user 1 with specific password "password"
        ]);
		
        $this->browse(function (Browser $browser) use ($user1) {
			
            $browser->visit('/logout')->logout() // log out user 0
					->visit('/login') // go to login page
					->type('email',$user1->email) // type in user email
					->type('password','password') // type in user password
					->press('Login') // press login button
					->visit('/posts/1') // go to the post's page
					->assertSee('REPLY') // assert that user has access to reply to the comment
					->press('REPLY') // press on reply
					->pause(5000) // the reply text area takes time to come up so wait a few seconds to make sure it has become interactable
					->type('#replytextarea','Test reply') // type a reply
					->press('#replysubmit') // submit the comment form
					->assertSee('Test reply'); // assert that the comment has been posted
					
        });
    }
	
    /**
     * Ensure we can edit a comment
     */
    public function testCommentEdit()
    {
        $user0 = User::first();
		
        $this->browse(function (Browser $browser) use ($user0) {
			
            $browser->visit('/logout')->logout() // log out user 1
					->visit('/login') // go to login page
					->type('email',$user0->email) // type in user email
					->type('password','password') // type in user password
					->press('Login') // press login button
					->visit('/posts/1') // go to the post's page
					->assertSee('EDIT') // assert that the poster has access to edit the comment
					->press('EDIT') // press on edit
					->pause(5000) // the edit text area takes time to come up so wait a few seconds to make sure it has become interactable
					->type('#edittextarea','Edited comment') // edit the comment
					->press('UPDATE') // press update
					->assertSee('Edited comment'); // assert that the comment has been correctly edited
					
        });
    }
	
    /**
     * Ensure we can delete a comment
     */
    public function testCommentDelete()
    {
		
        $this->browse(function (Browser $browser) {
			
            $browser->visit('/posts/1') // go to the post's page
					->assertSee('DELETE') // assert that the poster has access to delete the comment
					->clickLink('Delete') // press on delete
					->assertDontSee('Edited comment'); // assert that the comment has been correctly deleted
					
        });
    }
}
