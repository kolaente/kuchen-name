{ pkgs, lib, config, inputs, ... }:

{
  languages = {
    php = {
      enable = true;
      package = pkgs.php85.buildEnv {
        extensions = { all, enabled }: with all; enabled ++ [
          dom
          mbstring
          tokenizer
          xmlwriter
        ];
      };
    };
  };
}
