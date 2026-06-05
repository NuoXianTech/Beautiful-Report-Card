const isPrerelease = process.env.RELEASE_MODE === 'prerelease';
const releaseBranch = process.env.RELEASE_BRANCH || 'main';

module.exports = {
  branches: isPrerelease
    ? [{name: releaseBranch, prerelease: 'beta'}]
    : [releaseBranch],
  tagFormat: 'v${version}',
  plugins: [
    '@semantic-release/commit-analyzer',
    '@semantic-release/release-notes-generator',
    '@semantic-release/github',
  ],
};
