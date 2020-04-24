<?php

namespace App\Components;

use Domain\Post\Actions\AddVoteAction;
use Domain\Post\Actions\RemoveVoteAction;
use Domain\Post\Models\Post;
use Domain\User\Models\User;
use Livewire\Component;

class VoteButton extends Component
{
    protected ?User $user = null;

    protected AddVoteAction $addVoteAction;

    protected RemoveVoteAction $removeVoteAction;

    protected ?int $postId = null;

    protected int $voteCount = 0;

    public function mount($postId, $voteCount)
    {
        $this->postId = $postId;
        $this->voteCount = $voteCount;
        $this->user = current_user();
        $this->addVoteAction = app(AddVoteAction::class);
        $this->removeVoteAction = app(RemoveVoteAction::class);
    }

    public function render()
    {
        return view('components.voteButton', [
            'hasVoted' => $this->user && $this->user->votedFor($this->postId),
            'user' => $this->user,
            'voteCount' => $this->voteCount,
        ]);
    }

    public function toggleVote()
    {
        $post = Post::find($this->postId);

        if (! $post) {
            return;
        }

        if ($this->user->votedFor($post)) {
            $this->removeVoteAction->__invoke($post, $this->user);
        } else {
            $this->addVoteAction->__invoke($post, $this->user);
        }

        $this->voteCount = $post->vote_count;

        $this->user->refresh();
    }
}
