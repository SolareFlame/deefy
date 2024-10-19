<?php

namespace iutnc\deefy\renderer;

interface Renderer
{
    public function render(int $selector): string;
}
