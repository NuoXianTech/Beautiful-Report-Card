const isPrerelease = process.env.RELEASE_MODE === 'prerelease';

module.exports = {
  branches: isPrerelease
    ? [{name: 'main', prerelease: 'beta'}]
    : ['main'],
  tagFormat: 'v${version}',
  plugins: [
    '@semantic-release/commit-analyzer',
    '@semantic-release/release-notes-generator',
    '@semantic-release/github',
  ],
};
