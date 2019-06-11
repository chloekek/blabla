{pkgs ? import ./tools/pkgs.nix {}}:

let
    # Any packages listed here will be available in this Nix shell in which
    # tools/build is invoked. See ./build for more information.
    dependencies = [
        pkgs.php
        pkgs.phpPackages.composer
        pkgs.postgresql_11
        pkgs.sqitchPg
    ];
in

pkgs.stdenv.mkDerivation {
    name = "concrete";
    buildInputs = dependencies;
    phases = ["buildPhase"];
    buildPhase = ''
        2>&1 echo 'This derivation is only for use with nix-shell.'
        false
    '';
}
